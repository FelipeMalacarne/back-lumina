<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use SimpleXMLElement;

class OfxParser
{
    protected SimpleXMLElement $xml;

    public function load(string $ofxContent): self
    {
        $this->xml = new SimpleXMLElement(preg_replace('/^.*?<OFX>/s', '<OFX>', $ofxContent));

        return $this;
    }

    public function transactions(): array
    {
        $transactions = [];
        $statementTransactions = $this->xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKTRANLIST->STMTTRN;
        foreach ($statementTransactions as $transaction) {
            $transactions[] = [
                'type' => (string) $transaction->TRNTYPE,
                'date_posted' => $this->formatDate((string) $transaction->DTPOSTED),
                'amount' => (int) (round((float) $transaction->TRNAMT, 2) * 100),
                'fitid' => (string) $transaction->FITID,
                'memo' => (string) $transaction->MEMO,
            ];
        }

        return $transactions;
    }

    public function balance(): array
    {
        $balance = $this->xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->LEDGERBAL;

        return [
            'balance' => (int) (round((float) $balance->BALAMT, 2) * 100),
            'date' => $this->formatDate($balance->DTASOF),
        ];
    }

    public function account(): array
    {
        $account = $this->xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKACCTFROM;

        return [
            'bank_id' => (string) $account->BANKID,
            'account_id' => (string) $account->ACCTID,
            'account_type' => (string) $account->ACCTTYPE,
        ];
    }

    public function statement(): array
    {
        return [
            'transactions' => $this->transactions(),
            'balance' => $this->balance(),
            'account' => $this->account(),
        ];
    }

    public function currency(): string
    {
        return (string) $this->xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->CURDEF;
    }

    private function formatDate(string $dateString): string
    {
        try {
            return Carbon::parse($dateString)->format('Y-m-d H:i:s');
        } catch (InvalidArgumentException $e) {
            preg_match('/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})\[(-?\d+):\w+\]/', $dateString, $matches);
            $dateTime = new \DateTime(sprintf('%s-%s-%s %s:%s:%s', $matches[1], $matches[2], $matches[3], $matches[4], $matches[5], $matches[6]));
            $dateTime->setTimezone(new \DateTimeZone(sprintf('Etc/GMT%+d', $matches[7])));

            return Carbon::parse($dateTime->format(\DateTime::ISO8601_EXPANDED));
        }
    }
}
