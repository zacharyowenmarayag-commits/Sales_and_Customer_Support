# TODO - Make CRM Functionable (No Database)

## Storage + Controllers
- [ ] Add JSON file-based storage helper: `app/Support/CrmStorage.php`
- [ ] Update `CommunicationLogController@store` to write to JSON instead of Eloquent
- [ ] Update `CustomerController@store` to write to JSON instead of Eloquent
- [ ] Update `DashboardController` to compute counts/lists from JSON instead of Eloquent
- [ ] Update `PurchaseHistoryController` to generate rows by parsing JSON instead of Eloquent
- [ ] Update `FollowUpController@update` to update JSON by id (no route model binding)
- [ ] Update `routes/web.php` follow-up route to pass `{followUpId}`

## Validation / Compatibility
- [ ] Ensure Blade views still work (provide objects with expected properties; Carbon dates)

## Manual Testing
- [ ] Navigate: `/crm` dashboard
- [ ] Add customer and verify follow-up creation
- [ ] Add communication log with subject "Order Follow-up" and verify follow-up appears
- [ ] Update follow-up status via dropdown
- [ ] Verify purchase history export CSV works

