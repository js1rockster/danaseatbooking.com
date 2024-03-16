
CREATE TABLE users (
    user_id INT PRIMARY KEY,
    user_fullname VARCHAR(100) NOT NULL,
    user_name VARCHAR(30) NOT NULL,
    user_password VARCHAR(255) NOT NULL,
    user_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE customers (
    customer_id VARCHAR(255) PRIMARY KEY,
    user_id INT,
    customer_name VARCHAR(30) NOT NULL,
    customer_phone VARCHAR(10) NOT NULL,
    customer_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);



CREATE TABLE buses (
    bus_no VARCHAR(255) PRIMARY KEY,
    bus_cap VARCHAR(50) NOT NULL,
    bus_assigned TINYINT(1) NOT NULL DEFAULT 0,
    bus_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE drivers (
    id INT PRIMARY KEY,
    d_id VARCHAR(255) NOT NULL,
    d_name VARCHAR(30) NOT NULL,
    d_contact VARCHAR(10) NOT NULL,
    bus_no VARCHAR(155) NOT NULL,
    d_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bus_no) REFERENCES buses(bus_no)
);

CREATE TABLE routes (
    route_id VARCHAR(255) PRIMARY KEY,
    bus_no VARCHAR(155) NOT NULL,
    route_cities VARCHAR(255) NOT NULL,
    route_dep_date DATE NOT NULL,
    route_dep_time TIME NOT NULL,
    route_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (bus_no) REFERENCES buses(bus_no)
);

CREATE TABLE bookings (
    booking_id INT PRIMARY KEY,
    customer_id VARCHAR(255) NOT NULL,
    route_id VARCHAR(255) NOT NULL,
    customer_route VARCHAR(200) NOT NULL,
    travel_purpose VARCHAR(200) NOT NULL,
    booked_seat VARCHAR(100) NOT NULL,
    booking_created DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (route_id) REFERENCES routes(route_id)  -- Establishing the explicit relationship
);


CREATE TABLE seats (
    bus_no VARCHAR(155) NOT NULL,
    seat_booked VARCHAR(255) DEFAULT NULL,
    FOREIGN KEY (bus_no) REFERENCES buses(bus_no)
);

