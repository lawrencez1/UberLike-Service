drop table rides cascade constraints;
drop table deliveries cascade constraints;
drop table taxi cascade constraints;
drop table food cascade constraints;
drop table customers cascade constraints;
drop table drivers cascade constraints;
drop table fees cascade constraints;
drop table partnerRestaurants;
drop table addresses cascade constraints;
drop table insurancePolicies cascade constraints;
drop table vehicles cascade constraints;
drop table mechanics cascade constraints;
drop table regions cascade constraints;

CREATE TABLE regions
    (RegionID int,
    RegionName varchar(255) not null,
    FareMultiplier int not null,
    DeliveryFee int not null,
    City varchar(255) not null,
    State varchar(2) not null,
    PRIMARY KEY (RegionID),
    UNIQUE (RegionName));

CREATE TABLE mechanics
    (MechanicID int,
    StreetAddress varchar(255) not null,
    PostalCode varchar(255) not null,
    FirstName varchar(255),
    LastName varchar(255),
    PRIMARY KEY (MechanicID));

CREATE TABLE vehicles
    (VehicleID int,
    LastServed varchar(255),
    VehicleType varchar(255),
    MechanicID int,
    PRIMARY KEY (VehicleID),
    FOREIGN KEY (MechanicID) REFERENCES mechanics(MechanicID));

CREATE TABLE insurancePolicies
    (PolicyID int,
    kind varchar(255) not null,
    PRIMARY KEY (PolicyID));

CREATE TABLE addresses
    (StreetAddress varchar(255),
    PostalCode varchar(255),
    RegionID int,
    PRIMARY KEY (StreetAddress, PostalCode),
    FOREIGN KEY (RegionID) REFERENCES regions(RegionID) ON DELETE CASCADE);

CREATE TABLE partnerRestaurants
    (RestaurantID int,
    Name varchar(255),
    Cuisine varchar(255),
    StreetAddress varchar(255) not null,
    PostalCode varchar(255) not null,
    PRIMARY KEY (RestaurantID),
    UNIQUE (Name),
    FOREIGN KEY (StreetAddress, PostalCode) REFERENCES addresses(StreetAddress, PostalCode) ON DELETE CASCADE);

CREATE TABLE fees
    (FareMultiplier int,
    Distance int,
    Fare int not null,
    PRIMARY KEY(FareMultiplier, Distance));

CREATE TABLE drivers
    (DriverID int,
    firstName varchar(255) not null,
    lastName varchar(255) not null,
    Rating int,
    shiftTime varchar(255),
    shiftDays varchar(255),
    DriverType varchar(255),
    VehicleID int,
    PolicyID int,
    RegionID int,
    PRIMARY KEY (DriverID),
    FOREIGN KEY (VehicleID) REFERENCES vehicles(VehicleID),
    FOREIGN KEY (PolicyID) REFERENCES insurancePolicies(PolicyID),
    FOREIGN KEY (RegionID) REFERENCES regions(RegionID));

CREATE TABLE customers
    (CustomerID int,
    firstName varchar(255) not null,
    lastName varchar(255),
    Email varchar(255),
    Phone varchar(10),
    CreditCard varchar(16),
    StreetAddress varchar(255) not null,
    PostalCode varchar(255) not null,
    CustomerType varchar(255),
    UNIQUE (Email, Phone),
    PRIMARY KEY (CustomerID),
    FOREIGN KEY (StreetAddress, PostalCode) REFERENCES addresses(StreetAddress, PostalCode) ON DELETE CASCADE);

CREATE TABLE rides
    (RideID int,
    RideDate varchar(255),
    Distance int,
    DriverID int,
    RegionID int,
    CustomerID int,
    PRIMARY KEY (RideID),
    FOREIGN KEY (CustomerID) REFERENCES customers(CustomerID),
    FOREIGN KEY (DriverID) REFERENCES drivers(DriverID) ON DELETE CASCADE,
    FOREIGN KEY (RegionID) REFERENCES regions(RegionID));

CREATE TABLE deliveries
    (OrderID int,
    DeliveryDate varchar(255),
    CustomerID int,
    DriverID int,
    RestaurantID int,
    RegionID int,
    PRIMARY KEY (OrderID),
    FOREIGN KEY (CustomerID) REFERENCES customers(CustomerID),
    FOREIGN KEY (RestaurantID) REFERENCES partnerRestaurants(RestaurantID),
    FOREIGN KEY (DriverID) REFERENCES drivers(DriverID) ON DELETE CASCADE,
    FOREIGN KEY (RegionID) REFERENCES regions(RegionID));
    
CREATE TABLE taxi
    (DriverID int,
    PRIMARY KEY (DriverID),
    FOREIGN KEY (DriverID) REFERENCES drivers(DriverID) ON DELETE CASCADE);

CREATE TABLE food
    (DriverID int,
    PRIMARY KEY (DriverID),
    FOREIGN KEY (DriverID) REFERENCES drivers(DriverID) ON DELETE CASCADE);


insert into regions
values (1, 'Downtown Vancouver', 2, 6, 'Vancouver', 'BC');
insert into regions
values (2, 'Surrey', 1, 4, 'Surrey', 'BC');
insert into regions
values (3, 'Calgary West', 2, 5, 'Calgary', 'AB');
insert into regions
values (4, 'Calgary East', 1, 3, 'Calgary', 'AB');
insert into regions
values (5, 'UBC', 1, 3, 'Vancouver', 'BC');


insert into mechanics
values (1, '19 Eastwacth', 'V5Y 1G9', 'John', 'Snow');
insert into mechanics
values (2, '31 Winterfell', 'C5T 7D6', 'Aria', 'Stark');
insert into mechanics
values (3, '18 Eastwacth', 'V5Y 1G9', 'Sam', 'Tarley');
insert into mechanics
values (4, '1 Kings landing rd.', 'T7R 8H6', 'Rob', 'Baratheon');
insert into mechanics
values (5, '3 Valeria Street', 'R6T 9E6', 'Dan', 'Targ');

insert into vehicles
values (1, 'May 2019', 'Car', 5);
insert into vehicles
values (2, 'Jun 2018', 'Van', 4);
insert into vehicles
values (3, 'Jul 2019', 'Car', 4);
insert into vehicles
values (4, 'Mar 2021', 'Car', 2);
insert into vehicles
values (5, 'Jan 2020', 'Van', 1);

insert into insurancePolicies
values ('1', 'Driver');
insert into insurancePolicies
values ('2', 'Driver');
insert into insurancePolicies
values ('3', 'Driver');
insert into insurancePolicies
values ('4', 'Vehicle');
insert into insurancePolicies
values ('5', 'Vehicle');

insert into addresses
values ('1 Lower Mall', 'V6T 1Z4', 1);
insert into addresses
values ('2 Lower Mall', 'V6T 1Z4', 1);
insert into addresses
values ('5 King Street', 'V2P 8H7', 3);
insert into addresses
values ('293 Oxbridge Place', 'A1E 2A5', 5);
insert into addresses
values ('5 University Boulevard', 'A54 8B6', 5);
insert into addresses
values ('3 Valeria Street', 'R6T 9E6', 1);
insert into addresses
values ('1 Kings landing rd.', 'T7R 8H6', 1);
insert into addresses
values ('18 Eastwacth', 'V5Y 1G9', 2);
insert into addresses
values ('31 Winterfell', 'C5T 7D6', 4);
insert into addresses
values ('19 Eastwacth', 'V5Y 1G9', 2);

insert into partnerRestaurants
values (1, 'Mcdonalds', 'Fast Food', '293 Oxbridge Place', 'A1E 2A5');
insert into partnerRestaurants
values (2, 'JamJar', 'Middle Eastern', '5 University Boulevard', 'A54 8B6');
insert into partnerRestaurants
values (3, 'Tim Hortons', 'Breakfast', '1 Lower Mall', 'V6T 1Z4');
insert into partnerRestaurants
values (4, 'Triple Os', 'Fast Food', '2 Lower Mall', 'V6T 1Z4');
insert into partnerRestaurants
values (5, 'KFC', 'Fast Food', '5 King Street', 'V2P 8H7');

insert into fees
values (2, 4, 8);
insert into fees
values (1, 665, 665);
insert into fees
values (1, 8, 8);
insert into fees
values (2, 7, 14);
insert into fees
values (2, 34, 64);

insert into drivers
values (1, 'Lebron', 'James', 4, '08:00 to 16:00', 'MON', 'Taxi', 1, 2, 3);
insert into drivers
values (2, 'Chris', 'Paul', 3, '16:00 to 00:00', 'MON, TUE', 'Delivery', 1, 3, 3);
insert into drivers
values (3, 'Derrick', 'Rose', 5, '00:00 to 08:00', 'MON, WED, THUR', 'Taxi', 2, 2, 1);
insert into drivers
values (4, 'Blake', 'Griffin', 2, '08:00 to 16:00', 'THUR', 'Delivery', 2, 1, 2);
insert into drivers
values (5, 'Big', 'Shaq', 4, '12:00 to 20:00', 'FRI, SUN', 'Taxi', 5, 1, 2);

insert into customers
values (1, 'Nehal', 'Naeem', 'n@gmail.com', '1112223333', '111122223333444', '3 Valeria Street', 'R6T 9E6', 'Taxi');
insert into customers
values (2, 'Jeff', 'Jefferson', 'jeff@gmail.com', '5554591959', '546548734886454', '1 Kings landing rd.', 'T7R 8H6', 'Delivery');
insert into customers
values (3, 'Bob', 'Heles', 'bob@yelp.com', '5969748275', '569638648167468', '18 Eastwacth', 'V5Y 1G9', 'Taxi, Delivery');
insert into customers
values (4, 'James', 'James', 'james@gmail.com', '5838694932', '958390830958482', '31 Winterfell', 'C5T 7D6', 'Delivery');
insert into customers
values (5, 'Jon', 'Doe', 'jon@gmail.com', '4445556666', '1212121212121212', '19 Eastwacth', 'V5Y 1G9', 'Taxi');

    
insert into deliveries
values (1, 'Jan 8, 2020', 1, 4, 1, 2);    
insert into deliveries
values (2, 'Jan 9, 2020', 2, 2, 2, 3);    
insert into deliveries
values (3, 'Jan 10, 2020', 3, 2, 2, 3);    
insert into deliveries
values (4, 'Jan 11, 2020', 4, 4, 4, 2);    
insert into deliveries
values (5, 'Jan 12, 2020', 5, 2, 5, 3);

insert into rides
values (1, 'Jan 8, 2020', 4, 3, 1, 1);
insert into rides
values (2, 'Jan 8, 2020', 665, 5, 2, 2);
insert into rides
values (3, 'Jan 9, 2020', 6, 1, 3, 3);
insert into rides
values (4, 'Jan 9, 2020', 16, 5, 2, 4);
insert into rides
values (5, 'Jan 10, 2020', 34, 1, 3, 5);