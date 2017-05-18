-- remplacer la valeur de l'identifiant par l'identifiant correspondant à l'utilisateur que vous voulez effacer
SET @identifiant = 25 ;

-- ############################## rien à modifier############################

DELETE FROM admin_conversation
WHERE users_id = @identifiant ;

DELETE FROM cart
WHERE users_id = @identifiant ;

DELETE FROM professional_reviews
WHERE users_id = @identifiant ;

DELETE FROM stripe_subscriptions
WHERE users_id = @identifiant ;

DELETE FROM special_offers_usage
WHERE users_id = @identifiant ;

DELETE FROM mangopay_user_info
WHERE users_id = @identifiant ;

DELETE FROM mangopay_user_bank_account
WHERE user_id = @identifiant ;  # attention pas de S!!!

DELETE FROM orders
WHERE users_id = @identifiant ;

DELETE FROM order_items
WHERE users_id = @identifiant ;

DELETE FROM payments
WHERE users_id = @identifiant ;

DELETE FROM workshop
WHERE users_id = @identifiant ;

DELETE FROM user_addresses
WHERE users_id = @identifiant ;

DELETE FROM user_companies
WHERE users_id = @identifiant ;

DELETE FROM user_company_ids
WHERE users_id = @identifiant ;

DELETE FROM user_details
WHERE users_id = @identifiant ;

DELETE FROM user_hashes
WHERE users_id = @identifiant ;

DELETE FROM user_public_contact
WHERE users_id = @identifiant ;

DELETE FROM user_subscription
WHERE users_id = @identifiant ;

DELETE FROM user_subscription_fee
WHERE users_id = @identifiant ;

DELETE FROM user_roles
WHERE users_id = @identifiant ;

DELETE FROM user_wallet
WHERE users_id = @identifiant ;

DELETE FROM users
WHERE id = @identifiant ;
