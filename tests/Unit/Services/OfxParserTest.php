<?php

namespace Tests\Unit\Services;

use App\Services\OfxParser;
use Tests\TestCase;

class OfxParserTest extends TestCase
{
    protected string $sampleOfx;

    protected OfxParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sampleOfx = file_get_contents(__DIR__.'/sample.ofx');
        $this->parser = new OfxParser();
    }

    public function testLoadOfxContent()
    {
        $this->parser->load($this->sampleOfx);
        $this->assertInstanceOf(OfxParser::class, $this->parser);
    }

    public function testTransactions()
    {
        $this->parser->load($this->sampleOfx);
        $transactions = $this->parser->transactions();

        $this->assertIsArray($transactions);
        $this->assertNotEmpty($transactions);

        $sampleTransaction = $transactions[0];
        $this->assertArrayHasKey('type', $sampleTransaction);
        $this->assertArrayHasKey('date_posted', $sampleTransaction);
        $this->assertArrayHasKey('amount', $sampleTransaction);
        $this->assertArrayHasKey('fitid', $sampleTransaction);
        $this->assertArrayHasKey('memo', $sampleTransaction);
    }

    public function testBalance()
    {
        $this->parser->load($this->sampleOfx);
        $balanceData = $this->parser->balance();

        $this->assertIsArray($balanceData);
        $this->assertArrayHasKey('balance', $balanceData);
        $this->assertArrayHasKey('date', $balanceData);

        $this->assertEquals('162', $balanceData['balance']);
        $this->assertEquals('2024-01-31 03:00:00', $balanceData['date']);
    }

    public function testAccount()
    {
        $this->parser->load($this->sampleOfx);
        $accountData = $this->parser->account();

        $this->assertIsArray($accountData);
        $this->assertArrayHasKey('bank_id', $accountData);
        $this->assertArrayHasKey('account_id', $accountData);
        $this->assertArrayHasKey('account_type', $accountData);
    }

    public function testStatement()
    {
        $this->parser->load($this->sampleOfx);
        $statementData = $this->parser->statement();

        $this->assertIsArray($statementData);
        $this->assertArrayHasKey('transactions', $statementData);
        $this->assertArrayHasKey('balance', $statementData);
        $this->assertArrayHasKey('account', $statementData);
    }

    public function testCurrency()
    {
        $this->parser->load($this->sampleOfx);
        $currency = $this->parser->currency();

        $this->assertIsString($currency);
        // Add an assertion relevant to your OFX data:
        // $this->assertEquals('USD', $currency);
    }

    public function testTransactionsHasCorrectDateFormat()
    {
        $this->parser->load($this->sampleOfx);
        $transactions = $this->parser->transactions();

        $this->assertIsArray($transactions);
        $this->assertNotEmpty($transactions);

        $sampleTransaction = $transactions[0];
        $this->assertArrayHasKey('date_posted', $sampleTransaction);

        $this->assertEquals('2024-01-01 03:00:00', $sampleTransaction['date_posted']);
    }

    public function testBalanceHasCorrectDateFormat()
    {
        $this->parser->load($this->sampleOfx);
        $balanceData = $this->parser->balance();

        $this->assertIsArray($balanceData);
        $this->assertArrayHasKey('date', $balanceData);

        $this->assertEquals('2024-01-31 03:00:00', $balanceData['date']);
    }
}
