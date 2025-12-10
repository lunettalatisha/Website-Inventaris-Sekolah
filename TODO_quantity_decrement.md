# TODO: Implement Quantity Decrement on Borrowing

## Task: Make item quantity decrease when user borrows items

## Implementation Details
- [x] Update `storeUserBorrow` method in BorrowingController to decrement item quantity when user borrows
- [x] Ensure quantity is restored when item is returned (already implemented in returnItem method)
- [x] Ensure quantity is adjusted properly when borrowing is updated (already implemented in update method)
- [x] Ensure quantity is restored when borrowing is deleted (already implemented in destroy method)

## Status: Completed
The feature has been implemented. When a user borrows an item through the user interface, the item quantity is automatically decreased by the borrowed amount. The quantity is restored when the item is returned or when the borrowing record is deleted.
