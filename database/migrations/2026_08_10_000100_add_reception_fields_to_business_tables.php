<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hóa đơn (Orders) - lễ tân lập hóa đơn thanh toán dịch vụ/sản phẩm cho khách
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('orders', 'appointment_id')) {
                $table->unsignedBigInteger('appointment_id')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('orders', 'receptionist_id')) {
                $table->unsignedBigInteger('receptionist_id')->nullable()->after('appointment_id');
            }
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->decimal('total_amount', 12, 2)->default(0)->after('receptionist_id');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method', 30)->default('cash')->after('total_amount');
            }
            if (!Schema::hasColumn('orders', 'payment_status')) {
                $table->string('payment_status', 20)->default('unpaid')->after('payment_method');
            }
            if (!Schema::hasColumn('orders', 'note')) {
                $table->text('note')->nullable()->after('payment_status');
            }
        });

        // Chi tiết hóa đơn (Order details)
        Schema::table('order_details', function (Blueprint $table) {
            if (!Schema::hasColumn('order_details', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('order_details', 'service_id')) {
                $table->unsignedBigInteger('service_id')->nullable()->after('order_id');
            }
            if (!Schema::hasColumn('order_details', 'service_name')) {
                $table->string('service_name', 150)->nullable()->after('service_id');
            }
            if (!Schema::hasColumn('order_details', 'quantity')) {
                $table->integer('quantity')->default(1)->after('service_name');
            }
            if (!Schema::hasColumn('order_details', 'unit_price')) {
                $table->decimal('unit_price', 12, 2)->default(0)->after('quantity');
            }
            if (!Schema::hasColumn('order_details', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('unit_price');
            }
        });

        // Phản hồi khách hàng (Feedback) - lễ tân xem & xử lý phản hồi
        Schema::table('feedback', function (Blueprint $table) {
            if (!Schema::hasColumn('feedback', 'customer_id')) {
                $table->unsignedBigInteger('customer_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('feedback', 'appointment_id')) {
                $table->unsignedBigInteger('appointment_id')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('feedback', 'rating')) {
                $table->unsignedTinyInteger('rating')->default(5)->after('appointment_id');
            }
            if (!Schema::hasColumn('feedback', 'comment')) {
                $table->text('comment')->nullable()->after('rating');
            }
            if (!Schema::hasColumn('feedback', 'status')) {
                $table->string('status', 20)->default('new')->after('comment');
            }
        });

        // Thông báo nội bộ (Notifications) - nhắc việc cho lễ tân
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title', 150)->nullable()->after('id');
            }
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->nullable()->after('title');
            }
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type', 20)->default('info')->after('message');
            }
            if (!Schema::hasColumn('notifications', 'appointment_id')) {
                $table->unsignedBigInteger('appointment_id')->nullable()->after('type');
            }
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('appointment_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_id', 'appointment_id', 'receptionist_id', 'total_amount', 'payment_method', 'payment_status', 'note']);
        });

        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'service_id', 'service_name', 'quantity', 'unit_price', 'subtotal']);
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->dropColumn(['customer_id', 'appointment_id', 'rating', 'comment', 'status']);
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['title', 'message', 'type', 'appointment_id', 'is_read']);
        });
    }
};
