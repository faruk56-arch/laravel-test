DELETE FROM regions where country_id = 69;
INSERT INTO regions (name, country_id) VALUES ("Auvergne-Rhône-Alpes", 69),
                                              ("Bourgogne-Franche-Comté", 69),
                                              ("Bretagne", 69),
                                              ("Centre-Val de Loire", 69),
                                              ("Corse", 69),
                                              ("Grand Est", 69),
                                              ("Hauts-de-France", 69),
                                              ("Île-de-France", 69),
                                              ("Normandie", 69),
                                              ("Nouvelle-Aquitaine", 69),
                                              ("Occitanie", 69),
                                              ("Pays de la Loire", 69),
                                              ("Provence-Alpes-Côte d'Azur", 69);
UPDATE users SET region = 3894 where region > 1001 AND region < 1024 AND country_id = 69;
UPDATE merchants SET region = 3894 where region > 1001 AND region < 1024 AND country_id = 69;
