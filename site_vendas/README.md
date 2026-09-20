# XPC Informática — PHP Online Store

A PHP online store with authentication, shopping-cart, checkout, customer and administration areas, product management and stock control.

## Implemented features

- Sign-in
- User registration
- Shopping cart
- Checkout with order creation
- Customer area
- Administration panel
- Category management
- Product management
- Order management
- User management

## Initial administrator account

> Change these credentials before using the application outside a local development environment.

- Email: `admin@admin.com`
- Password: `Admin@123456`

## Structure

The application deliberately uses a simple structure:

- main pages in the project root;
- `inc/` for the database connection, shared functions and layout;
- `admin/` for the administration panel;
- `storage/database.json` as a lightweight data store.

## Purchase flow

The purchase flow supports:

1. adding products to the cart;
2. updating quantities;
3. completing checkout;
4. creating the order in the data store;
5. updating stock.

This version does not integrate an online payment gateway. Checkout records and completes the order internally.

## Author

**Quévin Tavares**
