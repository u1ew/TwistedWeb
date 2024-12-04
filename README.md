# TwistedWeb
What i have produced for the Twisted Web project with the following requirements:


TL Y2 - Project Overview
Develop a dynamic website for a car garage company that allows customers to book 
services, view service history, and contact the garage. The website will have an admin 
panel for managing services, bookings, and customer information.
Project Requirements
Functional Requirements
1. User Registration and Login:
Customers can register and log in to their accounts.
Admin can log in to manage the website.
2. Service Booking:
Customers can book services online.
Booking confirmation via their dashboard.
3. Service Management:
Admin can add, update, and delete services.
Display available services to customers.
4. Booking Management:
Admin can view and manage bookings.
Customers can view their booking history.
5. Contact Form:
Customers can send inquiries through a contact form (this will be displayed on the admin 
side of the system).
Admin can view and respond to inquiries.
6. Service History:
Customers can view their service history.
Non-Functional Requirements
1. Security:
Secure user authentication.
Data validation and sanitization.
2. Performance:
Fast loading times.
Efficient database queries.
3. Usability:
User-friendly interface.
Responsive design for mobile and desktop.
Database Design
Tables – The below are the recommended tables, there may be more that you will need to 
develop:
• users (id, name, email, password, role)
• services (id, name, description, price)
• bookings (id, user_id, service_id, booking_date, status)
• inquiries (id, user_id, message, response, created_at)
At the completion of your website system, you will be required to get feedback from others, 
this must include a review on the development of the website (functionality) and the code 
itself.