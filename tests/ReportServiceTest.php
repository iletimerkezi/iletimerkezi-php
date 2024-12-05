<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use IletiMerkezi\Services\ReportService;
use IletiMerkezi\Http\HttpClientInterface;
use IletiMerkezi\Responses\ReportResponse;
use Mockery;

/**
 * @covers \IletiMerkezi\Services\ReportService
 */
class ReportServiceTest extends TestCase
{
    private $httpClient;
    private $reportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpClient = Mockery::mock(HttpClientInterface::class);
        $this->reportService = new ReportService(
            $this->httpClient,
            'test-key',
            'test-hash'
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetReportSuccessfully()
    {
        $expectedResponse = [
            'response' => [
                'status' => [
                    'message' => 'Success'
                ],
                'order' => [
                    'id' => '12345',
                    'status' => '114',
                    'message' => [
                        ['number' => '905301234567', 'status' => '111']
                    ]
                ]
            ]
        ];

        $this->httpClient
            ->shouldReceive('post')
            ->once()
            ->with('get-report/json', Mockery::any())
            ->andReturnSelf();

        $this->httpClient
            ->shouldReceive('getBody')
            ->once()
            ->andReturn($expectedResponse);

        $this->httpClient
            ->shouldReceive('getStatusCode')
            ->once()
            ->andReturn(200);

        $response = $this->reportService->get(12345);

        $this->assertInstanceOf(ReportResponse::class, $response);
        $this->assertEquals('12345', $response->getOrderId());
        $this->assertEquals('COMPLETED', $response->getOrderStatus());
        $this->assertTrue($response->ok());
    }

    public function testNextPageThrowsExceptionWhenNoPreviousRequest()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No previous report request found. Call getReport() first.');
        
        $this->reportService->next();
    }
}