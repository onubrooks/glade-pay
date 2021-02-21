<?php

namespace onubrooks\GladePay\Tests;

use PHPUnit\Framework\TestCase;

use onubrooks\GladePay\GladePay;

final class GladePayTest extends TestCase
{
    /**
     * Get Private Method of a given class for testing
     *
     * @param 	string $className
     * @param 	string $methodName
     * @author	Abah Onuh <abahonuh@gmail.com>
     * @return	ReflectionMethod
     */
    public function getPrivateMethod($className, $methodName)
    {
        $reflector = new \ReflectionClass($className);
        $method = $reflector->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * Test Successful Class Creation
     *
     * @return void
     */
    public function testCreateClass()
    {
        $glade = new GladePay;

        $this->assertNotEmpty($glade);
    }

    /**
     * Test Glade API Exists
     *
     * @return void
     */
    public function testGladeAPIExists()
    {
        $glade = new GladePay;

        $method = $this->getPrivateMethod('onubrooks\GladePay\GladePay', 'fetch');

        $result = $method->invokeArgs($glade, array('GET', 'payment', []));

        $this->assertIsArray($result);

        $this->assertArrayHasKey('message', $result, $message = 'returned array has no such key');
    }

    /**
     * Test Zero Amount Transaction
     *
     * @return void
     */
    public function testBankTransferZeroAmountInvalidArgumentException()
    {
        $glade = new GladePay;

        $business_name = "Happy Glade Customer";

        $this->expectException(\InvalidArgumentException::class);
        
        $glade->bankTransfer(0, null, $business_name);
    }

    /**
     * Test Negative Amount Transaction
     *
     * @return void
     */
    public function testBankTransferNegativeAmountInvalidArgumentException()
    {
        $glade = new GladePay;

        $business_name = "Happy Glade Customer";

        $this->expectException(\InvalidArgumentException::class);
        
        $glade->bankTransfer(-10000, null, $business_name);
    }

    /**
     * Test Normal Amount Transaction
     *
     * @return array
     */
    public function testBankTransferSuccess()
    {
        $glade = new GladePay;

        $business_name = "Happy Glade Customer";

        $transaction = $glade->bankTransfer(10000, null, $business_name);

        $this->assertIsArray($transaction);

        $this->assertArrayHasKey('message', $transaction, $message = 'returned array has no such key');
        $this->assertArrayHasKey('txnRef', $transaction, $message = 'returned array has no such key');
        $this->assertArrayHasKey('accountNumber', $transaction, $message = 'returned array has no such key');
        $this->assertArrayHasKey('accountName', $transaction, $message = 'returned array has no such key');
        $this->assertArrayHasKey('bankName', $transaction, $message = 'returned array has no such key');
        $this->assertArrayHasKey('bankName', $transaction, $message = 'returned array has no such key');

        $this->assertEquals(202, $transaction['status']);

        return $transaction;
    }

    /**
     * Test Empty Transaction Ref Verification
     *
     * @return void
     */
    public function testVerifyEmptyTxnRefInvalidArgumentException()
    {
        $glade = new GladePay;

        $this->expectException(\InvalidArgumentException::class);

        $glade->verify('');

    }

    /**
     * Test Normal Transaction Ref Verification
     *
     * @return void
     */
    /**
     * @depends testBankTransferSuccess
     */
    public function testVerifySuccess($transaction)
    {
        $glade = new GladePay;

        $verification = $glade->verify($transaction["txnRef"]);

        $this->assertIsArray($verification);

        $this->assertArrayHasKey('message', $verification, $message = 'returned array has no such key');
        $this->assertArrayHasKey('txnRef', $verification, $message = 'returned array has no such key');
        $this->assertArrayHasKey('txnStatus', $verification, $message = 'returned array has no such key');
        $this->assertArrayHasKey('payment_method', $verification, $message = 'returned array has no such key');
        $this->assertArrayHasKey('bank_message', $verification, $message = 'returned array has no such key');

        $this->assertEquals(200, $verification['status']);
    }
}
