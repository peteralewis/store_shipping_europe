store_shipping_europe
=====================

Expresso Store Shipping rule for Europe

Provides additional selection of "Europe" in shipping rules to simplify general european 
location targetting for shipping pricing. This replaces the default shipping plugin as it 
extends it's functionality and prevents having to create duplicate rules for all the 
European countries.

Place into the Expresso Store shipping rules folder in your ExpressionEngine third_party folder.
Probably /third_party/expressionengine/third_party/store/libraries/store_shipping/

Because this extends the default plugin, you can use this as a replacement as it covers 
all locations, rather than have a separate method for Europe and other locations.

**Label**
Alas I haven't worked out how to override the automated language variable for the new class, 
so if you want a nice label to display in the settings page for this rule, you'll need to
add an entry to the language file.

Add this around line 160 to store_lang.php (/store/language/english folder):
    'store_shipping_europe'             => 'Europe',
