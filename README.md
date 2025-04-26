# Secure Login with Prepared Statements and Input Sanitization

This scenario implements a login page that is secure against **SQL Injection (SQLi)** attacks using best practices in PHP and MySQL.

## Key Security Features

- Prepared statements (`mysqli->prepare`) for all SQL queries
- `htmlspecialchars()` to sanitize output
- Session handling with status and user role verification
- No use of AI model — pure backend security

## How it Works

1. User submits credentials.
2. Code uses a parameterized SQL query to safely compare `userid` and `password`.
3. If authenticated, session is started and user is redirected.

## Testing Against SQLi

Try the following inputs — all should be blocked with **"Invalid credentials."**

- `' OR 1=1 --`
- `admin' --`
- `' UNION SELECT * FROM user --`

## Note

Even without AI or complex filters, **prepared statements alone** are very effective at stopping SQLi.

This is the **recommended approach** for real-world applications.

## Bonus

You can combine this with ML-based anomaly detection for a **defense-in-depth** strategy.
