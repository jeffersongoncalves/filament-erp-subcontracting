<?php

namespace JeffersonGoncalves\FilamentErp\Subcontracting\Concerns;

use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use JeffersonGoncalves\Erp\Core\Enums\DocStatus;
use JeffersonGoncalves\Erp\Subcontracting\Models\SubcontractingOrder;
use JeffersonGoncalves\Erp\Subcontracting\Services\SubcontractingOrderService;

/**
 * The "Create Receipt" record action for a submitted subcontracting order. It
 * hands off to {@see SubcontractingOrderService::createReceipt()} which drafts
 * the downstream subcontracting receipt, copying the outstanding finished-good
 * quantities and the supplied raw materials over as consumed. Any failure is
 * surfaced as a Filament danger notification.
 */
trait CreatesSubcontractingReceipts
{
    public static function createReceiptAction(): Action
    {
        return Action::make('createReceipt')
            ->label('Create Receipt')
            ->icon('heroicon-o-inbox-arrow-down')
            ->color('primary')
            ->requiresConfirmation()
            ->visible(fn (Model $record): bool => $record->getAttribute('docstatus') === DocStatus::Submitted)
            ->action(function (Model $record): void {
                if (! $record instanceof SubcontractingOrder) {
                    return;
                }

                try {
                    $receipt = app(SubcontractingOrderService::class)->createReceipt($record);

                    Notification::make()
                        ->title('Subcontracting receipt drafted')
                        ->body('Subcontracting Receipt #'.$receipt->getKey().' created.')
                        ->success()
                        ->send();
                } catch (\Throwable $exception) {
                    Notification::make()
                        ->title('Unable to create subcontracting receipt')
                        ->body($exception->getMessage())
                        ->danger()
                        ->send();
                }
            });
    }
}
