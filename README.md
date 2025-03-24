# Gadget Garden

**Gadget Garden** is a private e-commerce platform for selling tech accessories and gadgets. The platform offers an intuitive user experience and robust admin tools for managing products, orders, and inventory efficiently.
Gadget Garden is a private e-commerce platform dedicated to selling tech accessories and gadgets, with a strong commitment to environmental sustainability. Our marketplace not only allows users to purchase high-quality refurbished products but also enables them to sell their own tech items, promoting a circular economy. With an intuitive user experience and robust admin tools, Gadget Garden ensures efficient management of products, orders, and inventory. By fostering a community of eco-conscious consumers, we aim to reduce electronic waste while providing access to affordable and reliable technology solutions.
## Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Usage](#usage)
- [Security Considerations](#security-considerations)

## Features

- **Role-Based Access Control**: Supports three roles: `admin`, `manager`, and `user`. Role-specific content is displayed dynamically across all pages.

- **User Registration & Login**: Secure user authentication with password hashing.
- **Product Browsing & Filtering**: Search and filter products by category, price, and condition.
- **Shopping Cart System**: Add/remove products, view basket, and complete checkout. Basket clears upon successful order.
- **Checkout Form with Validation**: Ensures credit card numbers are 16 digits, and the expiration date is valid. Also auto-fills name and email.
- **Order Management**: Tracks status (`Paid → Dispatched → Delivered`). Admins and managers can update order statuses.
- **Order History**: Users can track their order details and return status.
- **Product Review System**: Users can leave reviews and ratings; average ratings are displayed using stars. Admin can view reviews.
- **Customer Contact & Reply**: Users can submit contact queries. Admins can reply, users can view responses. Admins see all replies; users only see their own.

- **Return Requests**: Users can initiate return requests and view return status.
- **Upload Tech for Sale**: Users can upload products for approval. Admins can approve or reject uploads.
- **Admin & Manager Dashboard**: Includes analytics with charts for:
  - Monthly revenue
  - Orders trend
  - New customer growth
  - Best-selling products

- **Inventory Management**:
  - Add/edit/update products
  - View low stock warnings
  - Admin/Manager access only

- **Manage Users Page**:
  - View all users
  - Update roles (admin/manager/user)

- **Hosting Compatibility Fixes**:
  - Debugged SQL issues from local → hosted environment due to stricter SQL modes
  - Ensured compatibility with hosted phpMyAdmin

- **Responsive Navbars**: Role-aware navigation bar components render appropriate options for admins, managers, and regular users.

## Technologies Used

- **Frontend**: 
  - HTML, CSS
  - JavaScript 
  
- **Backend**: 
  - PHP

- **Server**: 
  - Apache (XAMPP for local development)

- **Version Control**: 
  - Git (private repository hosted on GitHub)

## Usage

### For Admins:
- Access full analytics via dashboard
- Approve uploaded products
- Update order statuses
- Manage inventory and user roles
- View and reply to contact messages

### For Managers:
- Access full analytics via dashboard
- Approve uploaded products
- Update order statuses
- Edit inventory 
- View and reply to contact messages

### For Users:
- Browse and purchase tech products
- Upload products for admin review
- Submit support/contact queries
- Leave product reviews
- Track order status and initiate returns
- Manage profile, including name, email, and account ID

## Security Considerations

- **Password Hashing**: User passwords are hashed and salted using a secure algorithm (e.g., bcrypt) before storage.
- **User Input Validation**: All forms (signup, login, product creation, etc.) have thorough validation to prevent malicious input.
- **Error Handling**: The system provides meaningful error messages in case of validation failures or other issues during use.
- **SQL Injection Protection**: All database queries use prepared statements.
- **Access Control**: Users cannot access admin features without proper role authorization.


