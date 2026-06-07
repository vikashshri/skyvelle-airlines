 ✈ Skyvelle Airlines
### Airline Ticket Reservation System

> A full-stack airline reservation web application built with PHP & MySQL — featuring smart booking, seat selection, frequent flyer rewards, and a complete admin control panel.

---

## 🖥️ Tech Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP (MySQLi) |
| Database | MySQL |
| Server | Apache (XAMPP) |
| Icons | Font Awesome 6.5 |
| Fonts | Poppins, Cormorant Garamond, DM Sans |

---

## ✨ Features

### 👤 Customer
- **Register & Login** — Secure account creation and session-based authentication
- **Homepage** — Elegant landing page with flight search, destinations, features and footer
- **Search Flights** — Search by origin, destination, date, class and passengers
- **Book Tickets** — Multi-passenger booking with meal, lounge, insurance and priority check-in options
- **Interactive Seat Selection** — Visual cabin map with class-restricted seat picking (Economy / Business)
- **Payment** — Credit card, debit card, net banking and UPI with live card visual and DB storage
- **View Booked Tickets** — Upcoming and past trips with colour-coded status badges
- **Ticket Receipt** — Downloadable/printable e-ticket with full passenger details, barcode, and fare breakdown
- **Cancel Booking** — 4-step cancellation flow with reason selection and refund preview
- **Profile Page** — Personal details, FF status, mileage progress bar, recent bookings
- **Edit Profile** — Update name, email, phone, address with live password strength meter

### 🏆 Frequent Flyer Programme
- **Auto-enrol** after 3 bookings
- **Mileage auto-applied** when booking — no manual entry needed
- Progress bar showing points towards Platinum tier
- FF number auto-filled for returning customers

### 🔧 Admin Panel
- **Dashboard** — Live stats: revenue, bookings, customers, active flights
- **Top routes** with booking counts and bar charts
- **Add / Delete Flights**
- **Add / Deactivate Aircraft**
- **View all bookings** across all customers

---

## 📁 Project Structure

```
skyvelle/
│
├── homepage.php                          # Public landing page
├── login.php                             # Customer login
├── new_user.php                          # Customer registration
├── logout_handler.php                    # Session destroy
│
├── homepage.php                          # Customer dashboard
├── profile2.php                           # Customer profile
├── edit_profile.php                      # Edit profile & password
│
├── book_tickets.php                      # Flight search form
├── view_flights_form_handler.php         # Search handler
├── add_ticket_details.php                # Passenger details form
├── add_ticket_details_form_handler.php   # Booking + FF auto-enrol
├── select_seats.php                      # Interactive seat map
├── save_seats.php                        # Seat assignment handler
│
├── payment_details.php                   # Payment form
├── payment_details_form_handler.php      # Payment processing
├── ticket_success.php                    # Booking success page
│
├── view_booked_tickets.php               # My trips page
├── ticket_receipt.php                    # E-ticket receipt + print
│
├── cancel_booked_tickets.php             # Step 1: Enter PNR
├── cancel_review.php                     # Step 2: Review booking
├── cancel_reason.php                     # Step 3: Select reason
├── cancel_confirm.php                    # Step 4: Confirm
├── cancel_booked_tickets_form_handler.php# Cancellation handler
├── cancel_booked_tickets_success.php     # Refund success page
│
├── admin_dashboard.php                   # Admin control panel
├── admin_view_booked_tickets.php         # Admin: all bookings
├── add_flight_details.php                # Admin: add flight
├── delete_flight_details.php             # Admin: delete flight
├── add_jet_details.php                   # Admin: add aircraft
├── deactivate_jet_details.php            # Admin: deactivate jet
│
└── Database Connection file/
    └── mysqli_connect.php                # ⚠️ Not uploaded (contains credentials)
```

---


## 📸 Pages Overview

| Page | Description |
|---|---|
| Homepage | Landing page with search, destinations, features |
| Login / Register | Customer authentication |
| Book Tickets | Search → Passengers → Seats → Payment |
| My Trips | Upcoming and past bookings |
| Ticket Receipt | Printable e-ticket with barcode |
| Cancel Booking | 4-step flow with reason + refund |
| Profile | FF status, mileage, recent bookings |
| Admin Dashboard | Live stats, bookings, routes, quick actions |

---

## 👩‍💻 Developed By

Vikashshri S M — Full Stack PHP Developer  
Built as part of an academic project on airline reservation systems.

---

## 📄 License

This project is for educational purposes.

---

> *"Where Destination meets Dreams "* ✈ — Skyvelle Airlines
