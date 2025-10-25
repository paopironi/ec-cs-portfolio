describe("The Home Page", () => {
	it("successfully loads", () => {
		cy.visit("http://mktime.test/");
	});
	it("has the title 'MK TIME | Home'", () => {
		cy.visit("http://mktime.test/").title().should("eq", "MK TIME | Home");
	});
});

describe("The Register Page", () => {
	// before registering a new user remove all users from the database
	before(() => {
		cy.exec("npm run db:reset");
	});
	const first_name = "Paolo";
	const last_name = "Pironi";
	const username = "ppiron@gmail.com";
	const password = "secret";

	it("shows an error message if the username input is left blank", () => {
		cy.visit("http://mktime.test/register.php");
		cy.get("input[name=first_name]").type(`${first_name}`);
		cy.get("input[name=last_name]").type(`${last_name}`);
		cy.get("input[name=password]").type(`${password}`);
		cy.get("input[name=confirm_password]").type(`${password}{enter}`);
		cy.contains("Enter your email").should("be.visible");
	});

	it("shows an error message if the first name input is left blank", () => {
		cy.visit("http://mktime.test/register.php");
		cy.get("input[name=last_name]").type(`${last_name}`);
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}`);
		cy.get("input[name=confirm_password]").type(`${password}{enter}`);
		cy.contains("Enter your first name").should("be.visible");
	});

	it("shows an error message if the last name input is left blank", () => {
		cy.visit("http://mktime.test/register.php");
		cy.get("input[name=first_name]").type(`${first_name}`);
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}`);
		cy.get("input[name=confirm_password]").type(`${password}{enter}`);
		cy.contains("Enter your last name").should("be.visible");
	});

	it("shows an error message if the password is left blank", () => {
		cy.visit("http://mktime.test/register.php");
		cy.get("input[name=first_name]").type(`${first_name}`);
		cy.get("input[name=last_name]").type(`${last_name}`);
		cy.get("input[name=email]").type(`${username}{enter}`);
		cy.contains("Enter a password").should("be.visible");
	});

	it("shows an error message if the passwords do not match", () => {
		cy.visit("http://mktime.test/register.php");
		cy.get("input[name=first_name]").type(`${first_name}`);
		cy.get("input[name=last_name]").type(`${last_name}`);
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}`);
		cy.get("input[name=confirm_password]").type(`abc{enter}`);
		cy.contains("Passwords do not match").should("be.visible");
	});

	it("registers a new user", () => {
		cy.visit("http://mktime.test/register.php");
		cy.get("input[name=first_name]").type(`${first_name}`);
		cy.get("input[name=last_name]").type(`${last_name}`);
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}`);
		cy.get("input[name=confirm_password]").type(`${password}{enter}`);
		cy.contains("Successfully registered!").should("be.visible");
	});

	it("the new registered user can log in", () => {
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		cy.url().should("equal", "http://mktime.test/");
		cy.get(".dropdown-toggle").should("have.text", "Paolo");
	});
});

describe("The Login Page", () => {
	const username = "ppiron@gmail.com";
	const password = "secret";

	it("log user in with correct username and password", () => {
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		cy.url().should("equal", "http://mktime.test/");
		cy.get(".dropdown-toggle").should("have.text", "Paolo");
	});

	it("shows an error message if the wrong credentials are entered", () => {
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`abc{enter}`);
		cy.url().should("equal", "http://mktime.test/login_action.php");
		cy.contains("Wrong credentials").should("be.visible");
	});

	it("shows an error message if the username input is left blank", () => {
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=password]").type(`${password}{enter}`);
		cy.url().should("equal", "http://mktime.test/login_action.php");
		cy.contains("Enter your email").should("be.visible");
	});

	it("shows an error message if the password input is left blank", () => {
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`{enter}`);
		cy.url().should("equal", "http://mktime.test/login_action.php");
		cy.contains("Enter a valid password").should("be.visible");
	});
});

describe("The Logout Button", () => {
	const username = "ppiron@gmail.com";
	const password = "secret";

	it("log the user out and redirect to login page", () => {
		// Login
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		// Logout
		cy.contains("Logout").click();
		cy.url().should("equal", "http://mktime.test/login.php");
	});
});

describe("The Shop Page", () => {
	const username = "ppiron@gmail.com";
	const password = "secret";
	before(() => {
		// Logout and remove all orders from the database
		cy.clearAllCookies();
		cy.exec("npm run db:reset:orders");
	});

	it("cannot be visisted if the user is not logged in", () => {
		cy.visit("http://mktime.test/");
		cy.contains("Shop").click();
		cy.url().should("equal", "http://mktime.test/login.php");
	});

	it("loads successfully after login", () => {
		// Login
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		// Visit shop
		cy.contains("Shop").click();
		cy.url().should("equal", "http://mktime.test/products.php");
	});

	it("allows to add an item to the cart", () => {
		// Login
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		// Visit shop
		cy.contains("Shop").click();
		// Click on 'Add To Cart' button of item with id=3
		cy.get("input[name=item_id][value=3]").next().click();
		cy.url().should("equal", "http://mktime.test/added.php?id=3");
	});
});

describe("The Cart Page", () => {
	const username = "ppiron@gmail.com";
	const password = "secret";
	before(() => {
		// Logout and remove all orders from the database
		cy.clearAllCookies();
		cy.exec("npm run db:reset:orders");
	});

	it("lists items added to the cart", () => {
		// Login
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		// Visit shop
		cy.contains("Shop").click();
		// Click on 'Add To Cart' button of item name 'Mio'
		cy.contains("Mio").closest("div").contains("Add To Cart").click();
		// Go to the Cart page
		cy.contains("View Your Cart").click();
		// Assert that url is correct
		cy.url().should("equal", "http://mktime.test/cart.php");
		// Assert that there is a table row corresponding to the added item
		cy.contains("td", "Mio").should("be.visible");
		cy.contains("td", "£100.00").should("be.visible");
		cy.get("td > input[type=number]")
			.should("be.visible")
			.and("have.value", 1);
	});

	it("allows to change quantities in the cart", () => {
		// Login
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		// Visit shop
		cy.contains("Shop").click();
		// Click on 'Add To Cart' button of item name 'Mio'
		cy.contains("Mio").closest("div").contains("Add To Cart").click();
		// Go to the Cart page
		cy.contains("View Your Cart").click();
		// Find the input of type number with the quantity in the cart and increase the value to 2
		cy.get("td > input[type=number]").clear().type("2").trigger("change");
		// Click on the Update Cart button
		cy.contains("Update Cart").click();
		// Assert that the quantity and the subtotal have increased
		cy.contains("td", "£200.00").should("be.visible");
		cy.get("td > input[type=number]")
			.should("be.visible")
			.and("have.value", 2);
	});

	it("allows to remove and item by clicking the trash button next to the item", () => {
		// Login
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		// Visit shop
		cy.contains("Shop").click();
		// Click on 'Add To Cart' button of item name 'Mio'
		cy.contains("Mio").closest("div").contains("Add To Cart").click();
		// Go to the Cart page
		cy.contains("View Your Cart").click();
		// Find the input of type hidden whose value is the id of the item
		cy.get("td > input[type=hidden][value=3]")
			// and click on the button which is its closest sibling
			.next()
			.click();
		// Assert that the cart is empty
		cy.contains("Your cart is empty").should("be.visible");
	});

	it("allows to remove and item by setting its quantity to zero", () => {
		// Login
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		// Visit shop
		cy.contains("Shop").click();
		// Click on 'Add To Cart' button of item name 'Mio'
		cy.contains("Mio").closest("div").contains("Add To Cart").click();
		// Go to the Cart page
		cy.contains("View Your Cart").click();
		// Find the input of type number with the quantity in the cart and increase the value to 0
		cy.get("td > input[type=number]").clear().type("0").trigger("change");
		// Click on the Update Cart button
		cy.contains("Update Cart").click();
		// Assert that the cart is empty
		cy.contains("Your cart is empty").should("be.visible");
	});

	it("allows to place an order", () => {
		// Login
		cy.visit("http://mktime.test/login.php");
		cy.get("input[name=email]").type(`${username}`);
		cy.get("input[name=password]").type(`${password}{enter}`);
		// Visit shop
		cy.contains("Shop").click();
		// Click on 'Add To Cart' button of item name 'Mio'
		cy.contains("Mio").closest("div").contains("Add To Cart").click();
		// Go to the Cart page
		cy.contains("View Your Cart").click();
		cy.contains("Place Order").click();
		cy.url().should("equal", "http://mktime.test/order.php");
		cy.contains(/Success: Order #[1-9] placed/).should("be.visible");
		cy.get('a[href="orders.php"]').last().click();
		cy.url().should("equal", "http://mktime.test/orders.php");
		cy.contains("td", "Mio").should("be.visible");
		cy.contains("td", "£100.00").should("be.visible");
	});
});
