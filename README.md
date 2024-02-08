Create a Symfony PHP project using standard Symfony components with the following API and 
database functionality.
1) POST /api/orders 
Create a new order API endpoint
Request payload:
• Name
• Delivery address
• Order items (id, quantity)
• Delivery option
2) GET /api/orders
Create Get orders API, sending either order id or status in url param
Orders response should include all Order data from task 1 plus estimated delivery date & time and 
order status
3) PATCH /api/orders
Create Update order status API
Request payload: order id, new status
4) Create a command to find all “processing” orders that have passed their delivery time and 
update their status to “delayed”
Think about writing & using serializer, swagger, migrations and unit tests


Bundles/Packages Installed

NelmioApiDocBundle
Logger
Serializer
orm-pack 
doctrine/doctrine-migrations-bundle
symfony/uid
