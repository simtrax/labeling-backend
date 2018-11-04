<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    public function a_site_can_see_accounts()
    {
        $response = $this->json('GET', '/api/v1/account');

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
				[
					'id',
					'account',
					'short_no',
					'account_name',
					'fiscal_year',
					'is_spec',
					'is_text',
					'is_project',
					'is_quantity',
					'warn_when_debit',
					'warn_when_credit',
					'account_type',
					'vat_code',
					'item_group_number',
				],
			]);
    }

    public function stuff()
    {
		$invoiceLine = factory(InvoiceLine::class)->make();
		$invoiceLine->invoice_id = $this->invoice->id;
		
		$response = $this->json(
			'POST',
			'/api/v1/invoiceline',
			$invoiceLine->toArray()
		);
		
		$response
			->assertStatus(200)
			->assertJsonFragment([
				'invoice_id'		=> $this->invoice->id,
				'number' 			=> $invoiceLine->number,
				'name'				=> $invoiceLine->name,
				'co_worker'			=> $invoiceLine->co_worker,
				'account_id'		=> $invoiceLine->account_id,
				'vat_code'			=> $invoiceLine->vat_code,
				'vat_rate'			=> $invoiceLine->vat_rate,
				'vat_amount'		=> $invoiceLine->vat_amount,
				'cost_centre_id'	=> $invoiceLine->cost_centre_id,
				'cost_unit_id'		=> $invoiceLine->cost_unit_id,
				'project_id'		=> $invoiceLine->project_id,
				'quantity'			=> $invoiceLine->quantity,
				'unit'				=> $invoiceLine->unit,
				'get_unit_price'	=> $invoiceLine->get_unit_price,
				'unit_price'		=> $invoiceLine->unit_price,
				'discount'			=> $invoiceLine->discount,
				'amount'			=> $invoiceLine->amount,
				'comment'			=> $invoiceLine->comment,
				'extra_text'		=> $invoiceLine->extra_text,
				'is_invoice_fee'	=> $invoiceLine->is_invoice_fee,
				'purchase_price'	=> $invoiceLine->purchase_price,
				'gross_profit'		=> $invoiceLine->gross_profit,
				'text_line_number'	=> $invoiceLine->text_line_number,
				'sum_line_number'	=> $invoiceLine->sum_line_number,
            ]);
    }

    public function a_site_can_store_a_invoice_line_on_an_invoice()
	{
		$invoiceLine = $this->invoice->invoiceLines()->create(factory(InvoiceLine::class)->make()->toArray());

		
		$response = $this->json(
			'PATCH',
			'/api/v1/invoiceline/' . $invoiceLine->id,
			['name' => 'name']
		);

		$response
			->assertStatus(200)
			->assertJsonFragment([
				'name' => 'name',
			]);
		
	}

    public function a_site_can_delete_an_invoice_line()
	{
		$invoiceLine = $this->invoice->invoiceLines()->create(factory(InvoiceLine::class)->make()->toArray());

		$response = $this->json(
			'DELETE',
			'/api/v1/invoiceline/' . $invoiceLine->id
		);
		
		$response->assertStatus(200);
		
		$this->assertDatabaseMissing('invoice_lines', $invoiceLine->toArray());
	}
}
