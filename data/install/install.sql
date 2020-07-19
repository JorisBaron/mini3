CREATE TABLE `roles` (
    `id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`id`, `name`) VALUES
(0, 'Invité'), (1,'Utilisateur'), (2,'Administrateur');


CREATE TABLE `users` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `users_roles` (
    `id_user` INT NOT NULL,
    `id_role` INT NOT NULL,
    PRIMARY KEY (`id_user`, `id_role`),
    FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (`id_role`) REFERENCES `roles`(`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;