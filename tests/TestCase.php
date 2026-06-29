<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Tests;

use Composer\InstalledVersions;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use JeffersonGoncalves\Erp\Accounting\ErpAccountingServiceProvider;
use JeffersonGoncalves\Erp\Buying\ErpBuyingServiceProvider;
use JeffersonGoncalves\Erp\Core\ErpCoreServiceProvider;
use JeffersonGoncalves\Erp\Manufacturing\ErpManufacturingServiceProvider;
use JeffersonGoncalves\Erp\Stock\ErpStockServiceProvider;
use JeffersonGoncalves\Erp\Subcontracting\ErpSubcontractingServiceProvider;
use JeffersonGoncalves\FilamentErp\Core\Testing\InteractsWithErpFilament;
use JeffersonGoncalves\FilamentErp\Subcontracting\FilamentErpSubcontractingServiceProvider;
use JeffersonGoncalves\FilamentErp\Subcontracting\Tests\Fixtures\TestPanelProvider;
use JeffersonGoncalves\FilamentErp\Subcontracting\Tests\Fixtures\TestUser;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use InteractsWithErpFilament;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rebindFilamentDataStore();

        // The domain factories ship in the vendored packages; resolve them by
        // basename across the Subcontracting, Manufacturing, Buying, Stock,
        // Accounting and Core packages in turn.
        Factory::guessFactoryNamesUsing($this->erpFactoryResolver([
            'JeffersonGoncalves\\Erp\\Subcontracting\\Database\\Factories',
            'JeffersonGoncalves\\Erp\\Manufacturing\\Database\\Factories',
            'JeffersonGoncalves\\Erp\\Buying\\Database\\Factories',
            'JeffersonGoncalves\\Erp\\Stock\\Database\\Factories',
            'JeffersonGoncalves\\Erp\\Accounting\\Database\\Factories',
            'JeffersonGoncalves\\Erp\\Core\\Database\\Factories',
        ]));

        Filament::setCurrentPanel(Filament::getDefaultPanel());

        $this->withoutVite();

        $this->actingAs(TestUser::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]));
    }

    protected function getPackageProviders($app): array
    {
        return array_merge($this->filamentTestProviders(), [
            ErpCoreServiceProvider::class,
            ErpAccountingServiceProvider::class,
            ErpStockServiceProvider::class,
            ErpBuyingServiceProvider::class,
            ErpManufacturingServiceProvider::class,
            ErpSubcontractingServiceProvider::class,
            FilamentErpSubcontractingServiceProvider::class,
            TestPanelProvider::class,
        ]);
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $app['config']->set('auth.providers.users.model', TestUser::class);

        $coreConfig = InstalledVersions::getInstallPath('jeffersongoncalves/laravel-erp-core').'/config/erp-core.php';

        if (file_exists($coreConfig)) {
            $app['config']->set('erp-core', require $coreConfig);
        }

        $accountingConfig = InstalledVersions::getInstallPath('jeffersongoncalves/laravel-erp-accounting').'/config/erp-accounting.php';

        if (file_exists($accountingConfig)) {
            $app['config']->set('erp-accounting', require $accountingConfig);
        }

        $stockConfig = InstalledVersions::getInstallPath('jeffersongoncalves/laravel-erp-stock').'/config/erp-stock.php';

        if (file_exists($stockConfig)) {
            $app['config']->set('erp-stock', require $stockConfig);
        }

        $buyingConfig = InstalledVersions::getInstallPath('jeffersongoncalves/laravel-erp-buying').'/config/erp-buying.php';

        if (file_exists($buyingConfig)) {
            $app['config']->set('erp-buying', require $buyingConfig);
        }

        $manufacturingConfig = InstalledVersions::getInstallPath('jeffersongoncalves/laravel-erp-manufacturing').'/config/erp-manufacturing.php';

        if (file_exists($manufacturingConfig)) {
            $app['config']->set('erp-manufacturing', require $manufacturingConfig);
        }

        $subcontractingConfig = InstalledVersions::getInstallPath('jeffersongoncalves/laravel-erp-subcontracting').'/config/erp-subcontracting.php';

        if (file_exists($subcontractingConfig)) {
            $app['config']->set('erp-subcontracting', require $subcontractingConfig);
        }
    }

    protected function defineDatabaseMigrations(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->default('');
            $table->rememberToken();
        });

        $this->loadErpVendorMigrations([
            'core' => [
                'create_erp_companies_table',
                'create_erp_currencies_table',
                'create_erp_currency_exchanges_table',
                'create_erp_uoms_table',
                'create_erp_uom_conversions_table',
                'create_erp_fiscal_years_table',
                'create_erp_departments_table',
                'create_erp_designations_table',
                'create_erp_brands_table',
                'create_erp_terms_and_conditions_table',
                'create_erp_addresses_table',
                'create_erp_contacts_table',
                'create_erp_naming_series_table',
            ],
            'accounting' => [
                'create_erp_accounts_table',
                'create_erp_cost_centers_table',
                'create_erp_payment_terms_table',
                'create_erp_modes_of_payment_table',
                'create_erp_tax_templates_table',
                'create_erp_tax_template_taxes_table',
                'create_erp_banks_table',
                'create_erp_bank_accounts_table',
                'create_erp_budgets_table',
                'create_erp_budget_accounts_table',
                'create_erp_gl_entries_table',
                'create_erp_journal_entries_table',
                'create_erp_journal_entry_accounts_table',
                'create_erp_payment_entries_table',
                'create_erp_sales_invoices_table',
                'create_erp_sales_invoice_items_table',
                'create_erp_sales_invoice_taxes_table',
                'create_erp_purchase_invoices_table',
                'create_erp_purchase_invoice_items_table',
                'create_erp_purchase_invoice_taxes_table',
                'create_erp_period_closing_vouchers_table',
                'create_erp_bank_transactions_table',
            ],
            'stock' => [
                'create_erp_warehouses_table',
                'create_erp_items_table',
                'create_erp_price_lists_table',
                'create_erp_item_prices_table',
                'create_erp_batches_table',
                'create_erp_serial_nos_table',
                'create_erp_stock_ledger_entries_table',
                'create_erp_bins_table',
                'create_erp_stock_entries_table',
                'create_erp_stock_entry_details_table',
                'create_erp_material_requests_table',
                'create_erp_material_request_items_table',
                'create_erp_delivery_notes_table',
                'create_erp_delivery_note_items_table',
                'create_erp_purchase_receipts_table',
                'create_erp_purchase_receipt_items_table',
                'create_erp_stock_reconciliations_table',
                'create_erp_stock_reconciliation_items_table',
            ],
            'buying' => [
                'create_erp_supplier_groups_table',
                'create_erp_suppliers_table',
                'create_erp_buying_settings_table',
                'create_erp_request_for_quotations_table',
                'create_erp_request_for_quotation_items_table',
                'create_erp_request_for_quotation_suppliers_table',
                'create_erp_supplier_quotations_table',
                'create_erp_supplier_quotation_items_table',
                'create_erp_purchase_orders_table',
                'create_erp_purchase_order_items_table',
            ],
            'manufacturing' => [
                'create_erp_workstations_table',
                'create_erp_operations_table',
                'create_erp_boms_table',
                'create_erp_bom_items_table',
                'create_erp_bom_operations_table',
                'create_erp_routings_table',
                'create_erp_routing_operations_table',
                'create_erp_work_orders_table',
                'create_erp_work_order_items_table',
                'create_erp_job_cards_table',
                'create_erp_job_card_time_logs_table',
            ],
            'subcontracting' => [
                'create_erp_subcontracting_boms_table',
                'create_erp_subcontracting_bom_items_table',
                'create_erp_subcontracting_orders_table',
                'create_erp_subcontracting_order_items_table',
                'create_erp_subcontracting_order_supplied_items_table',
                'create_erp_subcontracting_receipts_table',
                'create_erp_subcontracting_receipt_items_table',
                'create_erp_subcontracting_receipt_supplied_items_table',
            ],
        ]);
    }
}
