import mysql from "mysql2/promise";

const conn = await mysql.createConnection({
	host: "127.0.0.1",
	user: "root",
	database: "mkt",
	multipleStatements: true,
});

try {
	const sql = "delete from `order_contents`";
	const [result, fields] = await conn.query(sql);
} catch (err) {
	console.log(err);
}

try {
	const sql = "delete from `orders`";
	const [result, fields] = await conn.query(sql);
} catch (err) {
	console.log(err);
}

try {
	const sql = "delete from `users`";
	const [result, fields] = await conn.query(sql);
} catch (err) {
	console.log(err);
}

await conn.end();
