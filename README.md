# Gadget Garden

**Gadget Garden** is a private e-commerce platform for selling tech accessories and gadgets. The platform offers an intuitive user experience and robust admin tools for managing products, orders, and inventory efficiently.
Gadget Garden is a private e-commerce platform dedicated to selling tech accessories and gadgets, with a strong commitment to environmental sustainability. Our marketplace not only allows users to purchase high-quality refurbished products but also enables them to sell their own tech items, promoting a circular economy. With an intuitive user experience and robust admin tools, Gadget Garden ensures efficient management of products, orders, and inventory. By fostering a community of eco-conscious consumers, we aim to reduce electronic waste while providing access to affordable and reliable technology solutions.
## Table of Contents

- [Features](#features)
- [Technologies Used](#technologies-used)
- [Usage](#usage)
- [Security Considerations](#security-considerations)

## Features

- **User Registration & Login**: Secure user authentication with password hashing and salting.
- **Product Browsing & Filtering**: Browse, search, and filter tech accessories by categories and attributes.
- **Shopping Cart & Checkout**: Manage items in the shopping cart, process orders, and complete checkout.
- **Order History**: Users can track their previous orders and view order details.
- **Admin Dashboard**: Admins can add, edit, and delete products, manage customer data, and process orders.
- **Real-time Inventory Management**: Automatic stock updates based on incoming and outgoing orders.
- **User Product Upload** *(Optional)*: Users can upload their own tech products for sale on the platform.

## Technologies Used

- **Frontend**: 
  - HTML, CSS
  - JavaScript (React, GSAP for animations)
  
- **Backend**: 
  - PHP

- **Server**: 
  - Apache (XAMPP for local development)

- **Version Control**: 
  - Git (private repository hosted on GitHub/GitLab)

## Usage

### For Admins:
- Admins can manage all products, view customer details, and track orders from the Admin Dashboard.
- The inventory system alerts admins about low stock and allows them to update product quantities in real-time.

### For Users:
- Users can browse products, filter by category, add items to their cart, and complete a checkout process.
- Registered users can view their order history and manage their personal details.

## Security Considerations

- **Password Hashing**: User passwords are hashed and salted using a secure algorithm (e.g., bcrypt) before storage.
- **User Input Validation**: All forms (signup, login, product creation, etc.) have thorough validation to prevent malicious input.
- **Error Handling**: The system provides meaningful error messages in case of validation failures or other issues during use.


