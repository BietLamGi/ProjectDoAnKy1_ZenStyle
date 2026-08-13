<?php

use App\Models\Appointment;
use App\Models\AppointmentService;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Service;

it('requires an existing customer when creating a receptionist appointment', function () {
    $service = Service::create([
        'ServiceType' => 0,
        'Category' => 'Spa',
        'ServiceName' => 'Facial',
        'Description' => 'Test service',
        'DurationMinutes' => 60,
        'Price' => 200000,
        'IsActive' => 1,
    ]);

    $response = $this->post(route('receptionist.appointments.store'), [
        'service_id' => $service->ServiceID,
        'appointment_date' => '2026-08-15',
        'appointment_time' => '10:00',
        'note' => 'Test note',
    ]);

    $response->assertSessionHasErrors('customer_id');
});

it('auto calculates final invoice amount from total and discount', function () {
    $customer = Customer::create([
        'FullName' => 'Alice',
        'Phone' => '0909000001',
        'LoyaltyPoints' => 0,
        'MembershipTier' => 'Normal',
    ]);

    $service = Service::create([
        'ServiceType' => 0,
        'Category' => 'Massage',
        'ServiceName' => 'Massage 60m',
        'Description' => 'Test service',
        'DurationMinutes' => 60,
        'Price' => 500000,
        'IsActive' => 1,
    ]);

    $appointment = Appointment::create([
        'CustomerID' => $customer->CustomerID,
        'StaffID' => null,
        'AppointmentDate' => '2026-08-15',
        'StartTime' => '10:00:00',
        'EndTime' => '11:00:00',
        'Status' => 'Confirmed',
        'Notes' => 'Test',
    ]);

    AppointmentService::create([
        'AppointmentID' => $appointment->AppointmentID,
        'ServiceID' => $service->ServiceID,
        'Quantity' => 1,
        'UnitPrice' => $service->Price,
    ]);

    $response = $this->post(route('receptionist.invoices.store'), [
        'AppointmentID' => $appointment->AppointmentID,
        'TotalAmount' => '500000',
        'DiscountAmount' => '50000',
        'PaymentMethod' => 'Cash',
    ]);

    $response->assertRedirect(route('receptionist.invoices.index'));

    $invoice = Invoice::where('AppointmentID', $appointment->AppointmentID)->firstOrFail();
    expect((float) $invoice->TotalAmount)->toBe(500000.0)
        ->and((float) $invoice->DiscountAmount)->toBe(50000.0)
        ->and((float) $invoice->FinalAmount)->toBe(450000.0);
});
