UPDATE inv_items AS i, purchases AS p, purchase_details as pd
SET i.rate = pd.unit_price, i.currency_id = p.currency
WHERE p.doc_number = pd.doc_number AND i.id = pd.item_id AND p.status = 'completed'
