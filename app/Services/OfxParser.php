<?php

namespace App\Services;

use Illuminate\Support\Carbon;
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
                'amount' => (int) ($transaction->TRNAMT * 100),
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
            'balance' => (string) $balance->BALAMT,
            'date' => Carbon::parse((string) $balance->DTASOF),
        ];
    }

    public function account(): array
    {
        $account = $this->xml->BANKMSGSRSV1->STMTTRNRS->STMTRS->BANKACCTFROM;

        $accid = explode('-', (string) $account->ACCTID);

        $bank_id = (string) $account->BANKID;
        // Remove leading zero, sometimes
        if (strlen($bank_id) > 3) {
            $bank_id = substr($bank_id, 1);
        }

        if (strlen($bank_id) < 3) {
            $bank_id = str_pad($bank_id, 3, '0', STR_PAD_LEFT);
        }

        return [
            'name' => (string) $this->xml->SIGNONMSGSRSV1?->SONRS?->FI?->ORG,
            'bank_id' => $bank_id,
            'check_digit' => $accid[1] ?? null,
            'number' => $accid[0],
            'type' => (string) $account->ACCTTYPE,
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
        preg_match('/(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})\[(-?\d+):\w+\]/', $dateString, $matches);
        $dateTime = new \DateTime(sprintf('%s-%s-%s %s:%s:%s', $matches[1], $matches[2], $matches[3], $matches[4], $matches[5], $matches[6]));
        $dateTime->setTimezone(new \DateTimeZone(sprintf('Etc/GMT%+d', $matches[7])));

        return Carbon::parse($dateTime->format(\DateTime::ISO8601_EXPANDED));
    }
}
