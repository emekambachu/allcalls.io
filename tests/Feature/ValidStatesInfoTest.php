<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Rules\ValidStatesInfo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidStatesInfoTest extends TestCase
{
    public function testValidStatesInfoRule()
    {
        $rule = new ValidStatesInfo();
    
        // Test valid input
        $validator = Validator::make([
            'states_info' => '{"Lead / call types":["AZ","CA"],"Auto Insurance":["AK"],"Final Expense":["AZ","AR"],"U65 Health":["AK"],"ACA":["AL","AZ","CO"],"Medicare\/Medicaid":["AK","HI","KY"],"Debt Relief":["AZ"]}'
        ]);
        $this->assertTrue($validator->passes());
    
        // Test invalid JSON input
        $validator = Validator::make(
            ['states_info' => '{invalid json}'],
            ['states_info' => $rule]
        );
        $this->assertFalse($validator->passes());
    
        // Test invalid structure input
        $validator = Validator::make(
            ['states_info' => '{"InvalidKey": "InvalidValue"}'],
            ['states_info' => $rule]
        );
        $this->assertFalse($validator->passes());
    }
}
