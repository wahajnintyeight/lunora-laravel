# Requirements Document

## Introduction

Lunora is a modern jewelry e-commerce platform built with Laravel 11 that enables customers to browse, purchase, and customize jewelry items. The platform supports both guest and registered user shopping experiences, with a comprehensive admin panel for managing products, orders, and customers. The system uses Pakistani Rupees (PKR) as the primary currency, stores product images locally, and provides essential e-commerce functionality without external payment gateways or cloud services initially.

## Requirements

### Requirement 1: User Authentication and Account Management

**User Story:** As a customer, I want to create an account and log in securely, so that I can save my preferences, track orders, and have a personalized shopping experience.

#### Acceptance Criteria

1. WHEN a user visits the registration page THEN the system SHALL display fields for name, email, and password
2. WHEN a user submits valid registration data THEN the system SHALL create an account and send an email verification link
3. WHEN a user clicks the email verification link THEN the system SHALL mark the account as verified
4. WHEN a user attempts to log in with valid credentials THEN the system SHALL authenticate them and redirect to their intended page
5. WHEN a user clicks "Login with Google" THEN the system SHALL redirect to Google OAuth and create or link their account
6. IF a user exists with the same email from Google OAuth THEN the system SHALL link the Google account to the existing user
7. WHEN a user requests password reset THEN the system SHALL send a reset link via email
8. WHEN a user is inactive for security reasons THEN the system SHALL prevent login and display appropriate message

### Requirement 2: Product Catalog Management

**User Story:** As an admin, I want to manage product categories, products, and their variants, so that customers can browse and purchase our jewelry collection.

#### Acceptance Criteria

1. WHEN an admin creates a category THEN the system SHALL allow setting name, slug, parent category, and active status
2. WHEN an admin creates a product THEN the system SHALL require category, name, SKU, price in PKR, and stock quantity
3. WHEN an admin uploads product images THEN the system SHALL store them locally and generate thumbnails
4. IF a product has variants like size or color THEN the system SHALL allow creating variants with separate SKUs and stock
5. WHEN a product variant has a price THEN the system SHALL use variant price, otherwise use product price
6. WHEN an admin sets a product as inactive THEN the system SHALL hide it from customer-facing pages
7. WHEN an admin adds product options THEN the system SHALL allow creating option groups like "Size" with values like "Small, Medium, Large"

### Requirement 3: Shopping Cart and Session Management

**User Story:** As a customer, I want to add items to my cart and have them persist across sessions, so that I can continue shopping and complete my purchase later.

#### Acceptance Criteria

1. WHEN a guest adds items to cart THEN the system SHALL store cart data using session ID
2. WHEN a logged-in user adds items to cart THEN the system SHALL associate cart with their user ID
3. WHEN a guest logs in with items in cart THEN the system SHALL merge session cart with user cart
4. WHEN a customer updates item quantity THEN the system SHALL validate against available stock
5. WHEN a customer removes an item THEN the system SHALL update cart totals immediately
6. WHEN a customer applies a coupon code THEN the system SHALL validate and apply the discount
7. IF stock becomes unavailable for cart items THEN the system SHALL notify the customer and update quantities

### Requirement 4: Checkout and Order Processing

**User Story:** As a customer, I want to complete my purchase by providing shipping information and placing an order, so that I can receive my jewelry items.

#### Acceptance Criteria

1. WHEN a customer starts checkout THEN the system SHALL require shipping and billing addresses
2. WHEN a customer enters address information THEN the system SHALL validate required fields
3. WHEN a customer places an order THEN the system SHALL generate a unique order number
4. WHEN an order is created THEN the system SHALL decrement stock for all ordered items
5. WHEN an order is placed THEN the system SHALL send confirmation email to customer
6. WHEN order total includes shipping THEN the system SHALL calculate and display shipping costs
7. IF a coupon is applied THEN the system SHALL store coupon code and discount amount with the order

### Requirement 5: Order Management and Fulfillment

**User Story:** As an admin, I want to manage customer orders and update their status, so that I can fulfill orders efficiently and keep customers informed.

#### Acceptance Criteria

1. WHEN an admin views orders list THEN the system SHALL display order number, customer, status, and total
2. WHEN an admin updates order status THEN the system SHALL send notification email to customer
3. WHEN an admin marks order as fulfilled THEN the system SHALL update status and timestamp
4. WHEN an admin cancels an order THEN the system SHALL restore stock for all items
5. WHEN an admin processes a refund THEN the system SHALL update order status and optionally restore stock
6. WHEN an admin views order details THEN the system SHALL display all items, addresses, and payment information
7. WHEN an admin prints order invoice THEN the system SHALL generate printable format with all order details

### Requirement 6: Inventory Management

**User Story:** As an admin, I want to track product inventory and receive alerts for low stock, so that I can maintain adequate stock levels.

#### Acceptance Criteria

1. WHEN a product is created THEN the system SHALL initialize stock quantity
2. WHEN an order is placed THEN the system SHALL automatically decrement stock
3. WHEN an order is cancelled THEN the system SHALL provide option to restore stock
4. WHEN stock reaches zero THEN the system SHALL mark product as out of stock
5. IF a product has variants THEN the system SHALL track stock separately for each variant
6. WHEN admin updates stock manually THEN the system SHALL log the change with timestamp
7. WHEN stock falls below threshold THEN the system SHALL highlight low stock items in admin panel

### Requirement 7: Coupon and Discount System

**User Story:** As an admin, I want to create and manage discount coupons, so that I can offer promotions to customers and increase sales.

#### Acceptance Criteria

1. WHEN an admin creates a coupon THEN the system SHALL allow setting code, type (fixed/percent), and value
2. WHEN an admin sets coupon dates THEN the system SHALL enforce start and end date restrictions
3. WHEN a customer applies a coupon THEN the system SHALL validate code, dates, and usage limits
4. IF coupon has minimum order value THEN the system SHALL check cart subtotal meets requirement
5. WHEN coupon has usage limits THEN the system SHALL track redemptions per user and total
6. WHEN fixed amount coupon is applied THEN the system SHALL discount minimum of coupon value or cart subtotal
7. WHEN percentage coupon is applied THEN the system SHALL calculate discount as percentage of subtotal

### Requirement 8: Admin Panel and User Management

**User Story:** As an admin, I want a comprehensive admin panel with modern UI components to manage all aspects of the e-commerce platform, so that I can efficiently operate the business.

#### Acceptance Criteria

1. WHEN an admin logs in THEN the system SHALL verify admin role and display admin dashboard with key metrics
2. WHEN an admin views dashboard THEN the system SHALL display sales statistics, recent orders, and inventory alerts
3. WHEN an admin manages users THEN the system SHALL allow viewing, editing, and deactivating accounts in a data table
4. WHEN an admin views activity logs THEN the system SHALL display user actions with timestamps and IP addresses
5. WHEN an admin manages categories THEN the system SHALL support hierarchical category structure with drag-and-drop reordering
6. WHEN an admin bulk updates products THEN the system SHALL allow selecting multiple products for batch operations
7. WHEN an admin exports data THEN the system SHALL generate CSV files for orders, products, and customers
8. WHEN admin interface loads THEN the system SHALL use responsive custom Tailwind-based components for optimal user experience
9. IF unauthorized user accesses admin routes THEN the system SHALL redirect to login with error message

### Requirement 9: Content Management and Static Pages

**User Story:** As an admin, I want to manage static content pages like About Us and Privacy Policy, so that I can provide important information to customers.

#### Acceptance Criteria

1. WHEN an admin creates a page THEN the system SHALL allow setting title, slug, and content
2. WHEN a customer visits a page URL THEN the system SHALL display the published content
3. WHEN an admin updates page content THEN the system SHALL save changes immediately
4. WHEN an admin sets page as unpublished THEN the system SHALL hide it from public access
5. WHEN page slug is changed THEN the system SHALL update the URL route
6. WHEN admin deletes a page THEN the system SHALL confirm action and remove from navigation
7. IF page slug conflicts with existing routes THEN the system SHALL prevent creation and show error

### Requirement 10: Email Notifications and Communication

**User Story:** As a customer, I want to receive email notifications about my account and orders, so that I stay informed about important updates.

#### Acceptance Criteria

1. WHEN a user registers THEN the system SHALL send email verification message
2. WHEN a user requests password reset THEN the system SHALL send reset link via email
3. WHEN an order is placed THEN the system SHALL send order confirmation email with details
4. WHEN order status changes THEN the system SHALL send status update email to customer
5. WHEN email sending fails THEN the system SHALL log error and continue processing
6. WHEN admin configures SMTP settings THEN the system SHALL use host's SMTP server
7. IF email address is invalid THEN the system SHALL handle gracefully and log the issue

### Requirement 11: Product Customization and Attributes

**User Story:** As a customer, I want to customize jewelry items with engravings and size options, so that I can personalize my purchase.

#### Acceptance Criteria

1. WHEN a product supports customization THEN the system SHALL display available options
2. WHEN a customer adds engraving text THEN the system SHALL store it with the cart item
3. WHEN a customer selects size options THEN the system SHALL validate against available variants
4. WHEN customized item is added to cart THEN the system SHALL display customization details
5. WHEN order is placed with customizations THEN the system SHALL include details in order confirmation
6. WHEN admin views order THEN the system SHALL display all customization attributes clearly
7. IF customization affects price THEN the system SHALL update item price accordingly

### Requirement 12: Search and Filtering

**User Story:** As a customer, I want to search for products and filter by categories, price, and attributes, so that I can quickly find items I'm interested in.

#### Acceptance Criteria

1. WHEN a customer enters search terms THEN the system SHALL search product names and descriptions
2. WHEN a customer filters by category THEN the system SHALL display only products in selected categories
3. WHEN a customer sets price range THEN the system SHALL filter products within specified PKR range
4. WHEN a customer applies multiple filters THEN the system SHALL combine all filter criteria
5. WHEN no products match filters THEN the system SHALL display "no results" message
6. WHEN customer clears filters THEN the system SHALL reset to show all products
7. WHEN search results are displayed THEN the system SHALL highlight matching terms

### Requirement 13: Mobile Responsiveness and Consistent Layout

**User Story:** As a customer using a mobile device, I want all pages including authentication pages to be fully responsive with consistent navigation, so that I can easily access and use the platform on any device.

#### Acceptance Criteria

1. WHEN a user visits any page on mobile devices THEN the system SHALL display a responsive layout optimized for screen sizes from 320px to 768px
2. WHEN a user visits authentication pages (login, register, forgot password, reset password) THEN the system SHALL include the complete header and footer components
3. WHEN a user accesses the site on mobile THEN the system SHALL display a collapsible hamburger menu for navigation
4. WHEN a user interacts with form elements on mobile THEN the system SHALL provide touch-friendly input fields with appropriate sizing (minimum 44px touch targets)
5. WHEN a user views the header on mobile THEN the system SHALL show a condensed version with logo, mobile menu toggle, and essential user actions (cart, account)
6. WHEN a user opens the mobile menu THEN the system SHALL display navigation links, search functionality, and user account options in a slide-out or dropdown format
7. WHEN a user clicks the catalog button THEN the system SHALL display a dropdown menu with product categories organized hierarchically
8. WHEN a user hovers over or clicks category items in the catalog dropdown THEN the system SHALL show subcategories and allow navigation to category pages
9. WHEN a user views authentication forms on mobile THEN the system SHALL optimize form layout with single-column design and appropriate spacing
10. WHEN a user accesses the site on tablet devices (768px-1024px) THEN the system SHALL provide an intermediate responsive layout between mobile and desktop
11. WHEN a user rotates their mobile device THEN the system SHALL maintain usability and proper layout in both portrait and landscape orientations
12. WHEN a user views product images on mobile THEN the system SHALL implement touch gestures for image gallery navigation (swipe, pinch-to-zoom)
13. WHEN a user interacts with the shopping cart on mobile THEN the system SHALL provide easy-to-use quantity controls and item management
14. WHEN a user completes checkout on mobile THEN the system SHALL optimize the multi-step process for mobile interaction with clear progress indicators
