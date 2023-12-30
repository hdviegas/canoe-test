<?php

namespace App\Http\Controllers;

use App\Events\DuplicatedFundWarning;
use App\Models\Fund;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;

class FundController extends Controller {
    /**
     * Display a list of funds optionally filtered by Name, Fund Manager, Year
     * and an option to return a list with all potential duplicates matching name and manager
     *
     * @param  Request  $request
     * @return Response
     */
    public function list(Request $request) {
        $name = $request->input('name');
        $fundManager = $request->input('fundManager');
        $year = $request->input('year');
        $duplicates = $request->input('duplicates');

        $funds = Fund::when($name, function ($query, $name) {
            return $query->where('name', $name);
        })
            ->when($fundManager, function ($query, $fundManager) {
                return $query->where('fund_manager_id', $fundManager);
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('start_year', $year);
            });

        if ($duplicates) {
            $funds = $funds->select('name', 'fund_manager_id', 'aliases')
                ->groupBy('name', 'fund_manager_id', 'aliases')
                ->havingRaw('COUNT(*) > 1')
                ->get();
        } else {
            $funds = $funds->get();
        }

        return response()->json($funds);
    }

    /**
     * Update the specified fund and its related attributes.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required',
            'fund_manager_id' => 'sometimes|exists:fund_managers,id',
            'start_year' => 'required|date_format:Y',
            'aliases' => 'required|array',
        ]);

        $fund = Fund::findOrFail($id);

        $fund->update($request->all());

        return response()->json($fund, 200);
    }

    /**
     * Store a newly created fund in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'fund_manager_id' => 'required|exists:fund_managers,id',
            'start_year' => 'required|date_format:Y',
            'aliases' => 'required|array',
        ]);

        $data = [
            'name' => $request->input('name'),
            'fund_manager_id' => $request->input('fund_manager_id'),
            'start_year' => $request->input('start_year'),
            'aliases' => $request->input('aliases'),
        ];

        $duplicateFund = Fund::where('fund_manager_id', $data['fund_manager_id'])
            ->where(function ($query) use ($data) {
                $query->where('name', $data['name']);
                $query->orWhereJsonContains('aliases', $data['name']);
            })
            ->first();

        if ($duplicateFund) {
            Event::dispatch(new DuplicatedFundWarning($duplicateFund));
        }

        $fund = Fund::create($data);

        return response()->json($fund, 201);
    }
}
