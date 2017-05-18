-- remplacer la valeur de l'identifiant par l'identifiant correspondant à l'utilisateur que vous voulez effacer
SET @identifiant = 25 ;

-- ############################## rien à modifier############################

DELETE FROM order_items
WHERE workshop_id = @identifiant ;

DELETE FROM cart
WHERE workshop_id = @identifiant ;

DELETE FROM mailbox
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_characteristics
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_equipments
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_campaign
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_description
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_locations
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_recurrence
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_availability
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_reviews
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_images
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_wall_images
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_currently_tags
WHERE workshop_id = @identifiant ;

DELETE FROM workshop_tags
WHERE workshop_id = @identifiant ;

DELETE FROM workshop
WHERE id = @identifiant ;
