### Phase 1 :

- I am focusing on Checkout Function Which is The Most Important Function In the Project.

## Problems Founded:

    1. Order Table Has A Relation With User Table(Employee or Admin) and In storing data In Order Table Iam not Passing ID of The Employee so This came with an error.
    2. For a One checkout Request Contains 10 Items(Products) it Produces 29 Queries 14 of them are duplicated
        -> Decrement the Quantity of the products in checkout request are decremented one by one and it takes so many queries as much as products number.
            => Fixing it throw DB::case
            => Before Using it the decrement queries equal 10 (As the number of Products)
            => After Using it The Queries are Only One Query
        -> At Validating if the products in the request exists it produces queries as number of products.
            => Fixing it by using Laravel Rules
            => Before Using This Method The queries equal 10 (As the number of Products)
            => After Using It The Queries are Only One Query
        ## After Fixing The Two Problems The queries Are fixed on 10 even after Increasing the number of Products in the request ##
    3. There Is no Lock to Prevent Race-condition in making checkout
        =>Fixing Throw Using lockForUpdate()

### Phase 2:

- Reviewing cancel Order Function

## Problems Founded:

    1. The Same Problem of decrement Stock Happens with increment stock when cancelling the order
        => Fixing it throw DB::case
        =>Before using it number of queries equals 17 (can increase if products in the order increased)
        => After using it number of queries downs to 8 (Fixed)

    2. There Is no Lock to Prevent Race-condition in making checkout
        =>Fixing Throw Using lockForUpdate()

### Phase 3:

- Reviewing Report Generation Logic

## Problems Founded:

    1. TopEmployeesReport Is Fixed for the current month and fixed for the status of orders filtered(Only Completed Orders)
        => Used Builder Design Pattern to make the report generation process more flexable and more reusable
    2. The same problem in Products Report Generation and the same solution used for it

### Phase 4:

- Protecting Important Routes (Checkout , cancel order and Report generating) From DDOS attack by applying RateLimiting on them
- Applying Idempotency For Checkout to protect it from double request

### Phase 5:

- At any POS system there is a Notification system that sends an email or notification to the admin if there is any product reaches the threshold

- Added `low_stock_threshold` to the products table.
- Created `SendLowStockAlertJob` to handle notifications asynchronously via Redis queues.
- Used `afterCommit()` during dispatch to prevent database lock timeouts.
- Batched low-stock products into a single job to bypass SMTP rate limits (Mailtrap 550 error) and optimize performance.
