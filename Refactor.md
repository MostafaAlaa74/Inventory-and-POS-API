# Refactoring and Performance Improvements

This document summarizes the key problems identified and the improvements made across the checkout, order cancellation, reporting, API security, and background-processing workflows.

## Phase 1: Checkout Optimization

### Unassigned order relationship

- **Problem:** Checkout failed because `employee_id` was missing when creating an order.
- **Fix:** Explicitly pass the authenticated user's ID.

### Excessive queries during stock decrement

- **Problem:** A checkout with 10 items generated 29 queries, including 14 duplicates, because stock was decremented item by item.
- **Fix:** Use a raw `DB::case` conditional update.
- **Result:** Reduce stock-decrement queries from 10 or more to a single query, keeping total checkout queries at 10 regardless of cart size.

### Excessive queries during validation

- **Problem:** Product-existence validation generated one query per item.
- **Fix:** Use Laravel's array validation rules with `exists:products,id`.
- **Result:** Reduce validation queries from 10 or more to a single query.

### Checkout race conditions

- **Problem:** Concurrent checkouts could oversell products.
- **Fix:** Apply pessimistic locking with `lockForUpdate()` to serialize database transactions.

## Phase 2: Order Cancellation Refactoring

### Cancellation logic in a generic CRUD method

- **Problem:** Cancellation logic was tightly coupled to the generic CRUD update method.
- **Fix:** Move the cancellation workflow into a dedicated action and endpoint, following the Single Responsibility Principle (SRP).

### Excessive queries during stock restoration

- **Problem:** Restoring inventory one item at a time caused unnecessary queries.
- **Fix:** Use `DB::case` logic.
- **Result:** Reduce cancellation queries from 17 to 8.

### Cancellation race conditions

- **Problem:** Concurrent cancellation requests risked incrementing stock more than once.
- **Fix:** Apply pessimistic locking with `lockForUpdate()` to protect inventory restoration.

## Phase 3: Dynamic Report Generation

### Rigid report architecture

- **Problem:** `TopEmployeesReport` and `TopSellingProductsReport` were hardcoded for the current month and specific order statuses.
- **Fix:** Implement the Builder design pattern to decouple query construction and make report generation flexible, chainable, and reusable across date ranges and business filters.

## Phase 4: API Security and Resilience

### Unprotected critical endpoints

- **Problem:** Critical endpoints, including checkout, cancellation, and reports, were not protected against brute-force attempts or script abuse.
- **Fix:** Apply custom rate limiting through `Laravel RouteServiceProvider` (for example, `throttle:checkout` and `throttle:reports`).

### Duplicate operations from repeated requests

- **Problem:** Double-clicks or network glitches could cause duplicate financial operations when users submitted payment or cancellation requests more than once.
- **Fix:** Add custom idempotency middleware using atomic `Cache::lock()` locks in Redis. Identical requests within a defined timeframe receive the same cached response without reaching the database.

## Phase 5: Asynchronous Event Processing

### Synchronous low-stock notifications

- **Problem:** Sending low-stock email alerts synchronously delayed checkout responses and frequently triggered Mailtrap 550 rate-limit errors.
- **Fix:** Add a `low_stock_threshold` to the database and move notifications to background jobs using Redis queues and `SendLowStockAlertJob`.

### Queue and notification optimizations

1. Use `afterCommit()` to dispatch jobs only after database locks are released.
2. Batch low-stock products from a single cart into one consolidated email and job to help avoid SMTP rate limits.
