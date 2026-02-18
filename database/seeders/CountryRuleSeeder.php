<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CountryRule;

class CountryRuleSeeder extends Seeder
{
    public function run()
    {
        $countries = [

            ['AF','AFGHANISTAN','AFN',['accountNumber','swiftBic']],
            ['AX','ALAND ISLANDS','EUR',['iban']],
            ['AL','ALBANIA','ALL',['iban','swiftBic']],
            ['DZ','ALGERIA','DZD',['accountNumber','iban','swiftBic','phone']],
            ['AS','AMERICAN SAMOA','USD',['accountNumber','swiftBic']],
            ['AD','ANDORRA','EUR',['iban','swiftBic']],
            ['AO','ANGOLA','AOA',['iban','swiftBic']],
            ['AG','ANTIGUA AND BARBUDA','XCD',['accountNumber','swiftBic']],
            ['AR','ARGENTINA','ARS',['accountNumber','swiftBic','routing','phone']],
            ['AM','ARMENIA','AMD',['accountNumber','swiftBic','routing']],
            ['AW','ARUBA','AWG',['accountNumber','swiftBic']],
            ['AU','AUSTRALIA','AUD',['accountNumber','swiftBic','routing','address']],
            ['AT','AUSTRIA','EUR',['iban']],
            ['AZ','AZERBAIJAN','AZN',['iban','swiftBic']],
            ['BS','BAHAMAS','BSD',['accountNumber','swiftBic']],
            ['BH','BAHRAIN','BHD',['iban','swiftBic']],
            ['BD','BANGLADESH','BDT',['accountNumber','swiftBic']],
            ['BB','BARBADOS','BBD',['accountNumber','swiftBic','address']],
            ['BY','BELARUS','BYN',['iban','swiftBic']],
            ['BE','BELGIUM','EUR',['iban']],
            ['BZ','BELIZE','BZD',['accountNumber','swiftBic']],
            ['BJ','BENIN','XOF',['iban','swiftBic']],
            ['BM','BERMUDA','BMD',['accountNumber','swiftBic']],
            ['BT','BHUTAN','BTN',['accountNumber','swiftBic']],
            ['BO','BOLIVIA','BOB',['accountNumber','swiftBic']],
            ['BA','BOSNIA AND HERZEGOVINA','BAM',['iban','swiftBic','phone']],
            ['BW','BOTSWANA','BWP',['accountNumber','swiftBic']],
            ['BR','BRAZIL','BRL',['iban','swiftBic','routing','phone']],
            ['BN','BRUNEI','BND',['accountNumber','swiftBic']],
            ['BG','BULGARIA','BGN',['iban']],
            ['BF','BURKINA FASO','XOF',['iban','swiftBic']],
            ['BI','BURUNDI','BIF',['accountNumber','swiftBic']],
            ['CV','CAPE VERDE','CVE',['iban','swiftBic']],
            ['KH','CAMBODIA','KHR',['accountNumber','swiftBic']],
            ['CM','CAMEROON','XAF',['iban','swiftBic']],
            ['CA','CANADA','CAD',['accountNumber','swiftBic','address','phone']],
            ['KY','CAYMAN ISLANDS','KYD',['accountNumber','swiftBic']],
            ['CF','CENTRAL AFRICAN REPUBLIC','XAF',['iban','swiftBic']],
            ['TD','CHAD','XAF',['iban','swiftBic']],
            ['CL','CHILE','CLP',['accountNumber','swiftBic']],
            ['CN','CHINA','CNY',['accountNumber','swiftBic','routing']],
            ['CO','COLOMBIA','COP',['accountNumber','swiftBic']],
            ['KM','COMOROS','KMF',['iban','swiftBic']],
            ['CG','CONGO','XAF',['iban','swiftBic']],
            ['CR','COSTA RICA','CRC',['iban','swiftBic']],
            ['CI','COTE DIVOIRE','XOF',['iban','swiftBic']],
            ['HR','CROATIA','EUR',['iban','swiftBic']],
            ['CY','CYPRUS','EUR',['iban']],
            ['CZ','CZECH REPUBLIC','CZK',['iban']],
            ['DK','DENMARK','DKK',['iban']],
            ['DO','DOMINICAN REPUBLIC','DOP',['iban','swiftBic']],
            ['EC','ECUADOR','USD',['accountNumber','swiftBic']],
            ['EG','EGYPT','EGP',['iban','swiftBic']],
            ['EE','ESTONIA','EUR',['iban']],
            ['FI','FINLAND','EUR',['iban']],
            ['FR','FRANCE','EUR',['iban']],
            ['DE','GERMANY','EUR',['iban']],
            ['GH','GHANA','GHS',['accountNumber','swiftBic']],
            ['GR','GREECE','EUR',['iban']],
            ['HK','HONG KONG','HKD',['accountNumber','swiftBic']],
            ['HU','HUNGARY','HUF',['iban','swiftBic']],
            ['IN','INDIA','INR',['accountNumber','swiftBic','routing','accountType']],
            ['ID','INDONESIA','IDR',['accountNumber','swiftBic','address']],
            ['IE','IRELAND','EUR',['iban']],
            ['IT','ITALY','EUR',['iban']],
            ['JP','JAPAN','JPY',['accountNumber','swiftBic']],
            ['KE','KENYA','KES',['accountNumber','swiftBic']],
            ['KR','SOUTH KOREA','KRW',['accountNumber','swiftBic']],
            ['LU','LUXEMBOURG','EUR',['iban']],
            ['MY','MALAYSIA','MYR',['accountNumber','swiftBic']],
            ['MX','MEXICO','MXN',['accountNumber','swiftBic']],
            ['NL','NETHERLANDS','EUR',['iban']],
            ['NG','NIGERIA','NGN',['accountNumber','swiftBic']],
            ['NZ','NEW ZEALAND','NZD',['accountNumber','swiftBic','routing']],
            ['NO','NORWAY','NOK',['iban','swiftBic']],
            ['PH','PHILIPPINES','PHP',['accountNumber','swiftBic','address']],
            ['PL','POLAND','PLN',['iban','swiftBic']],
            ['PT','PORTUGAL','EUR',['iban']],
            ['RO','ROMANIA','RON',['iban','swiftBic']],
            ['SG','SINGAPORE','SGD',['accountNumber','swiftBic']],
            ['ZA','SOUTH AFRICA','ZAR',['accountNumber','swiftBic','routing']],
            ['ES','SPAIN','EUR',['iban']],
            ['SE','SWEDEN','SEK',['iban','swiftBic']],
            ['CH','SWITZERLAND','CHF',['iban']],
            ['TH','THAILAND','THB',['accountNumber','swiftBic']],
            ['TR','TURKEY','TRY',['iban','swiftBic']],
            ['UG','UGANDA','UGX',['accountNumber','swiftBic']],
            ['UA','UKRAINE','UAH',['iban','swiftBic']],
            ['AE','UNITED ARAB EMIRATES','AED',['iban','swiftBic']],
            ['GB','UNITED KINGDOM','GBP',['iban']],
            ['US','UNITED STATES','USD',['accountNumber','routing','accountType']],

        ];

        foreach ($countries as $country) {
            CountryRule::updateOrCreate(
                ['country_iso' => $country[0]],
                [
                    'country_name' => $country[1],
                    'currency_iso' => $country[2],
                    'rules' => $country[3],
                ]
            );
        }
    }
}
