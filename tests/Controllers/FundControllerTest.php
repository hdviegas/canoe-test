<?php

namespace Tests\Unit;

use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Laravel\Lumen\Testing\DatabaseMigrations;

use App\Models\Fund;
use App\Models\FundManager;

class FundControllerTest extends TestCase {
    use DatabaseMigrations, DatabaseTransactions;

    public function testUpdate() {
        $fund = Fund::factory()->create();
        $updatedData = [
            'name' => 'Updated Fund Name',
            'fund_manager_id' => $fund->fund_manager_id,
            'start_year' => '2022',
            'aliases' => ['alias1', 'alias2'],
        ];

        $response = $this->put("/funds/{$fund->id}", $updatedData);

        $response->assertResponseStatus(200);

        $fund->refresh();

        $this->assertEquals($updatedData['name'], $fund->name);
        $this->assertEquals($updatedData['fund_manager_id'], $fund->fund_manager_id);
        $this->assertEquals($updatedData['start_year'], $fund->start_year);
        $this->assertEquals($updatedData['aliases'], $fund->aliases);
    }

    public function testStoreRequiresFields() {
        $response = $this->post('/funds', []);

        $response->assertResponseStatus(422);
        $response->seeJson([
            'name' => ['The name field is required.'],
            'fund_manager_id' => ['The fund manager id field is required.'],
            'start_year' => ['The start year field is required.'],
            'aliases' => ['The aliases field is required.'],
        ]);
    }

    public function testStoreChecksFundManagerExists() {
        $newFundData = [
            'name' => 'New Fund',
            'fund_manager_id' => 9999, // non-existing id
            'start_year' => '2022',
            'aliases' => ['alias1', 'alias2'],
        ];

        $response = $this->post('/funds', $newFundData);

        $response->assertResponseStatus(422);
        $response->seeJson([
            'fund_manager_id' => ['The selected fund manager id is invalid.'],
        ]);
    }

    public function testStoreDuplicateFundDispatchEvent() {
        $fundManager = FundManager::factory()->create();
        $fund = Fund::factory()->create(['fund_manager_id' => $fundManager->id]);

        $newFundData = [
            'name' => $fund->name,
            'fund_manager_id' => $fundManager->id,
            'start_year' => '2022',
            'aliases' => ['alias1', 'alias2'],
        ];

        $this->expectsEvents(\App\Events\DuplicatedFundWarning::class);

        $response = $this->post('/funds', $newFundData);

        $response->assertResponseStatus(201);
    }

    public function testStoreCreatesNewFund() {
        $fundManager = FundManager::factory()->create();
        $newFundData = [
            'name' => 'New Fund',
            'fund_manager_id' => $fundManager->id,
            'start_year' => '2022',
            'aliases' => ['alias1', 'alias2'],
        ];

        $response = $this->post('/funds', $newFundData);

        $response->assertResponseStatus(201);

        $response_data = $response->response->getData(true);

        $fund = Fund::find($response_data['id']);

        $this->assertEquals($newFundData['name'], $fund->name);
        $this->assertEquals($newFundData['fund_manager_id'], $fund->fund_manager_id);
        $this->assertEquals($newFundData['start_year'], $fund->start_year);
        $this->assertEquals($newFundData['aliases'], $fund->aliases);
    }

    public function testList() {
        $fund = Fund::factory()->create();

        $response = $this->get('/funds');

        $response->assertResponseStatus(200);

        $response_data = $response->response->getData(true);

        $this->assertEquals($fund->name, $response_data[0]['name']);
        $this->assertEquals($fund->fund_manager_id, $response_data[0]['fund_manager_id']);
        $this->assertEquals($fund->start_year, $response_data[0]['start_year']);
        $this->assertEquals($fund->aliases, $response_data[0]['aliases']);
    }

    public function testListFiltersByFundManagerId() {
        $fundManager = FundManager::factory()->create();
        $fund = Fund::factory()->create(['fund_manager_id' => $fundManager->id]);

        $response = $this->get("/funds?fund_manager_id={$fundManager->id}");

        $response->assertResponseStatus(200);

        $response_data = $response->response->getData(true);

        $this->assertEquals($fund->name, $response_data[0]['name']);
        $this->assertEquals($fund->fund_manager_id, $response_data[0]['fund_manager_id']);
        $this->assertEquals($fund->start_year, $response_data[0]['start_year']);
        $this->assertEquals($fund->aliases, $response_data[0]['aliases']);
    }

    public function testListFiltersByFundManagerName() {
        $fundManager = FundManager::factory()->create();
        $fund = Fund::factory()->create(['fund_manager_id' => $fundManager->id]);

        $response = $this->get("/funds?fund_manager_name={$fundManager->name}");

        $response->assertResponseStatus(200);

        $response_data = $response->response->getData(true);

        $this->assertEquals($fund->name, $response_data[0]['name']);
        $this->assertEquals($fund->fund_manager_id, $response_data[0]['fund_manager_id']);
        $this->assertEquals($fund->start_year, $response_data[0]['start_year']);
        $this->assertEquals($fund->aliases, $response_data[0]['aliases']);
    }

    public function testListFiltersByYear() {
        $fund = Fund::factory()->create(['start_year' => '2022']);

        $response = $this->get('/funds?start_year=2022');

        $response->assertResponseStatus(200);

        $response_data = $response->response->getData(true);

        $this->assertEquals($fund->name, $response_data[0]['name']);
        $this->assertEquals($fund->fund_manager_id, $response_data[0]['fund_manager_id']);
        $this->assertEquals($fund->start_year, $response_data[0]['start_year']);
        $this->assertEquals($fund->aliases, $response_data[0]['aliases']);
    }

    public function testListFiltersByDuplicate() {
        $fund = Fund::factory()->create(['name' => 'Fund Name']);
        $fund2 = Fund::factory()->create(['name' => 'Fund Name']);

        $response = $this->get('/funds?duplicates=true');

        $response->assertResponseStatus(200);

        $response_data = $response->response->getData(true);

        $this->assertEquals($fund->name, $response_data[0]['name']);
        $this->assertEquals($fund->fund_manager_id, $response_data[0]['fund_manager_id']);
        $this->assertEquals($fund->aliases, $response_data[0]['aliases']);
        $this->assertEquals($fund2->name, $response_data[1]['name']);
        $this->assertEquals($fund2->fund_manager_id, $response_data[1]['fund_manager_id']);
        $this->assertEquals($fund2->aliases, $response_data[1]['aliases']);
    }
}
