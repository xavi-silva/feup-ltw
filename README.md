# Ctrl + Sell

## Group ltw10g06

- Miguel Fernandes (up202207547) 30%
- Rui Xavier Silva (up202012345) 35%
- Tomás Vinhas (up202207183) 35%

## Install Instructions

    git clone git@github.com:FEUP-LTW-2024/ltw-project-2024-ltw10g06.git
    git checkout final-delivery-v1
    sqlite3 database/ctrlsell.db < database/database.sql
    php -S localhost:9000



## Screenshots
<img src="images/login_image.jpg" alt="LOGIN PAGE">

<img src="images/item_image.jpg" alt="ITEM PAGE">

## Implemented Features

**General**:

- [X] Register a new account.
- [X] Log in and out.
- [X] Edit their profile, including their name, username, password, and email.

**Sellers**  should be able to:

- [X] List new items, providing details such as category, brand, model, size, and condition, along with images.
- [X] Track and manage their listed items.
- [X] Respond to inquiries from buyers regarding their items and add further information if needed.
- [X] Print shipping forms for items that have been sold.

**Buyers**  should be able to:

- [ ] Browse items using filters like category, price, and condition.
- [X] Engage with sellers to ask questions or negotiate prices.
- [X] Add items to a wishlist or shopping cart.
- [X] Proceed to checkout with their shopping cart (simulate payment process).

**Admins**  should be able to:

- [X] Elevate a user to admin status.
- [X] Introduce new item categories, sizes, conditions, and other pertinent entities.
- [X] Oversee and ensure the smooth operation of the entire system.

**Security**:
We have been careful with the following security aspects:

- [X] **SQL injection**
- [X] **Cross-Site Scripting (XSS)**
- [ ] **Cross-Site Request Forgery (CSRF)**

**Password Storage Mechanism**: hash_password&verify_password

**Aditional Requirements**:

We also implemented the following additional requirements (you can add more):

- [ ] **Rating and Review System**
- [ ] **Promotional Features**
- [ ] **Analytics Dashboard**
- [ ] **Multi-Currency Support**
- [ ] **Item Swapping**
- [ ] **API Integration**
- [ ] **Dynamic Promotions**
- [ ] **User Preferences**
- [ ] **Shipping Costs**
- [ ] **Real-Time Messaging System**
- [X] **Admins can remove categories and conditions**
- [X] **Sellers can edit their items**
- [X] **Sellers can remove their items**
- [X] **Admins can remove items**
- [X] **Buy and Sell history**
