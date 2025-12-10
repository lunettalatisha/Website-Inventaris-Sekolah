# TODO: Add Reason for Borrowing Feature

## Steps to Implement
- [ ] Create migration to add 'reason' column to borrowings table
- [ ] Update Borrowing model to include 'reason' in fillable
- [ ] Modify borrowing form in items.blade.php to include textarea for reason
- [ ] Update storeUserBorrow method in BorrowingController to validate and save reason
- [ ] Update admin borrow create form to include reason field
- [ ] Update admin borrow edit form to include reason field
- [ ] Update admin borrow index view to display reason
- [ ] Update BorrowingExport to include reason column
- [ ] Run migration to apply database changes
