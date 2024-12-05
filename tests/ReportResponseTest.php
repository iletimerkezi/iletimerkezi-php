<?php

namespace Tests\Responses;

use PHPUnit\Framework\TestCase;
use IletiMerkezi\Responses\ReportResponse;

/**
 * @covers \IletiMerkezi\Responses\ReportResponse
 */
class ReportResponseTest extends TestCase
{
    private $sampleData = [
        'response' => [
            'status' => [
                'message' => 'Success'
            ],
            'order' => [
                'id' => '12345',
                'status' => '114',
                'message' => [
                    [
                        'number' => '905301234567',
                        'status' => '111'
                    ],
                    [
                        'number' => '905301234568',
                        'status' => '112'
                    ]
                ]
            ]
        ]
    ];

    public function testReportResponseParsesOrderDataCorrectly()
    {
        $response = new ReportResponse($this->sampleData, 200);

        $this->assertEquals('12345', $response->getOrderId());
        $this->assertEquals('COMPLETED', $response->getOrderStatus());
        $this->assertEquals(114, $response->getOrderStatusCode());
        $this->assertTrue($response->ok());
    }

    public function testReportResponseImplementsIteratorCorrectly()
    {
        $response = new ReportResponse($this->sampleData, 200);
        $messages = [];

        foreach ($response as $message) {
            $messages[] = $message;
        }

        $this->assertCount(2, $messages);
        $this->assertEquals('905301234567', $messages[0]['number']);
        $this->assertEquals('905301234568', $messages[1]['number']);
    }

    public function testMessageStatusTranslation()
    {
        $response = new ReportResponse($this->sampleData, 200);

        $this->assertEquals('DELIVERED', $response->getMessageStatus(0));
        $this->assertEquals('UNDELIVERED', $response->getMessageStatus(1));
    }
}
