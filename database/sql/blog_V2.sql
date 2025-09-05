-- =========================================
-- DEV RESET (MySQL 8+)
-- =========================================
DROP DATABASE IF EXISTS blog;
CREATE DATABASE blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog;

-- drop in case the table exists (respect FK order)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS
    entity_labels, blog_images, portfolio_images,
    portfolio_tech, techs,
    likes, portfolios, comments, blogs,
    access_histories, testimonials, users;
SET FOREIGN_KEY_CHECKS = 1;


-- =========================================
-- CORE
-- =========================================
CREATE TABLE users (
                       id                BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                       email             VARCHAR(191) NOT NULL,
                       password          VARCHAR(255) NOT NULL,
                       user_type         TINYINT NOT NULL DEFAULT 1,         -- 0=admin, 1=user
                       first_name        VARCHAR(100) NULL,
                       last_name         VARCHAR(100) NULL,
                       avatar            VARCHAR(1024) NULL,
                       register_type     TINYINT NOT NULL DEFAULT 0,         -- 0=local, 1=github, 2=google, 3=linkedin
                       address           VARCHAR(255) NULL,
                       phone_num         VARCHAR(20) NULL,
                       bio               TEXT NULL,
                       job_title         VARCHAR(100) NULL,
                       birthday          DATE NULL,
                       instagram_url     VARCHAR(255) NULL,
                       linkedin_url      VARCHAR(255) NULL,
                       github_url        VARCHAR(255) NULL,
                       email_verified_at DATETIME NULL,
                       remember_token    VARCHAR(100) NULL,
                       created_at        DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                       updated_at        DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                       PRIMARY KEY (id),
                       UNIQUE KEY uk_users_email (email),
                       KEY idx_users_user_type (user_type),
                       CONSTRAINT chk_users_user_type CHECK (user_type IN (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE access_histories (
                                  id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                                  user_id     BIGINT UNSIGNED NOT NULL,
                                  ip          VARCHAR(45) NULL,
                                  session_id  VARCHAR(128) NULL,
                                  signed_in_at DATETIME NULL,
                                  signed_out_at DATETIME NULL,
                                  created_at  DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                                  updated_at  DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                  PRIMARY KEY (id),
                                  KEY idx_history_user (user_id),
                                  CONSTRAINT fk_hist_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- BLOGS
-- =========================================
CREATE TABLE blogs (
                       id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                       user_id      BIGINT UNSIGNED NOT NULL,
                       title        VARCHAR(500) NOT NULL,
                       slug         VARCHAR(255) NULL,
                       contents     LONGTEXT NULL,                      -- Quill HTML
                       image_url    VARCHAR(1024) NULL,                 -- optional cover/thumbnail
                       created_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                       updated_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                       PRIMARY KEY (id),
                       UNIQUE KEY uk_blogs_slug (slug),
                       KEY idx_blogs_user (user_id),
                       KEY idx_blogs_published (created_at),
                       FULLTEXT KEY ft_blogs_title_contents (title, contents),
                       CONSTRAINT fk_blogs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- COMMENTS (threaded)
CREATE TABLE comments (
                          id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                          blog_id     BIGINT UNSIGNED NOT NULL,
                          user_id     BIGINT UNSIGNED NOT NULL,
                          parent_id   BIGINT UNSIGNED NULL,               -- for replies
                          group_no    BIGINT UNSIGNED NULL,               -- thread grouping for top-level
                          depth       TINYINT NOT NULL DEFAULT 0,         -- 0=root, 1=reply, 2=sub-reply
                          state       TINYINT NOT NULL DEFAULT 1,         -- 0=deleted,1=normal,2=edited
                          contents    VARCHAR(4000) NOT NULL,
                          created_at  DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                          updated_at  DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                          PRIMARY KEY (id),
                          KEY idx_comments_blog (blog_id),
                          KEY idx_comments_user (user_id),
                          KEY idx_comments_parent (parent_id),
                          KEY idx_comments_group (group_no),
                          KEY idx_comments_blog_created (blog_id, created_at),
                          CONSTRAINT fk_comments_blog  FOREIGN KEY (blog_id)  REFERENCES blogs(id)  ON DELETE CASCADE,
                          CONSTRAINT fk_comments_user  FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
                          CONSTRAINT fk_comments_parent FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- BLOG IMAGES (gallery)
CREATE TABLE blog_images (
                             id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                             blog_id    BIGINT UNSIGNED NOT NULL,
                             url        VARCHAR(1024) NOT NULL,
                             alt_text   VARCHAR(255) NULL,
                             caption    VARCHAR(255) NULL,
                             position   INT NOT NULL DEFAULT 0,
                             is_cover   TINYINT(1) NOT NULL DEFAULT 0,
                             created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                             updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                             PRIMARY KEY (id),
                             UNIQUE KEY uk_blog_images_order (blog_id, position),
                             KEY idx_blog_images_blog (blog_id),
                             KEY idx_blog_images_cover (blog_id, is_cover, position),
                             CONSTRAINT fk_blog_images_blog FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- PORTFOLIOS (Projects)
-- =========================================
CREATE TABLE portfolios (
                            id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                            user_id      BIGINT UNSIGNED NOT NULL,
                            title        VARCHAR(255) NOT NULL,
                            slug         VARCHAR(255) NULL,
                            description  TEXT NULL,
                            project_url  VARCHAR(1024) NULL,
                            image_url    VARCHAR(1024) NULL,                -- optional cover
                            like_count   INT NOT NULL DEFAULT 0,
                            created_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                            updated_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            PRIMARY KEY (id),
                            UNIQUE KEY uk_portfolios_slug (slug),
                            KEY idx_portfolios_user (user_id),
                            KEY idx_portfolios_created (created_at),
                            FULLTEXT KEY ft_portfolios_search (title, description),
                            CONSTRAINT fk_portfolios_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PORTFOLIO IMAGES (gallery)
CREATE TABLE portfolio_images (
                                  id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                                  portfolio_id BIGINT UNSIGNED NOT NULL,
                                  url          VARCHAR(1024) NOT NULL,
                                  alt_text     VARCHAR(255) NULL,
                                  caption      VARCHAR(255) NULL,
                                  position     INT NOT NULL DEFAULT 0,
                                  is_cover     TINYINT(1) NOT NULL DEFAULT 0,
                                  created_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                                  updated_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                  PRIMARY KEY (id),
                                  UNIQUE KEY uk_portfolio_images_order (portfolio_id, position),
                                  KEY idx_portfolio_images_portfolio (portfolio_id),
                                  KEY idx_portfolio_images_cover (portfolio_id, is_cover, position),
                                  CONSTRAINT fk_portfolio_images_portfolio FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- LIKES (for portfolios)
CREATE TABLE likes (
                       id            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                       portfolio_id  BIGINT UNSIGNED NOT NULL,
                       user_id       BIGINT UNSIGNED NOT NULL,
                       created_at    DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                       PRIMARY KEY (id),
                       UNIQUE KEY uk_likes_portfolio_user (portfolio_id, user_id),
                       KEY idx_likes_user (user_id),
                       CONSTRAINT fk_likes_portfolio FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE CASCADE,
                       CONSTRAINT fk_likes_user      FOREIGN KEY (user_id)      REFERENCES users(id)      ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- TECHS
-- =========================================
CREATE TABLE techs (
                       id        BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                       slug      VARCHAR(80)  NOT NULL,     -- 'react','laravel'
                       name      VARCHAR(120) NOT NULL,     -- display name
                       logo_url  VARCHAR(512) NULL,
                       created_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                       updated_at DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                       PRIMARY KEY (id),
                       UNIQUE KEY uk_techs_slug (slug),
                       UNIQUE KEY uk_techs_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PIVOT: portfolios <-> techs
CREATE TABLE portfolio_tech (
                                portfolio_id BIGINT UNSIGNED NOT NULL,
                                tech_id      BIGINT UNSIGNED NOT NULL,
                                level        TINYINT NULL,              -- 1..5
                                version      VARCHAR(40) NULL,          -- '18', '10.x'
                                is_primary   TINYINT(1) NOT NULL DEFAULT 0,
                                sort_order   INT NOT NULL DEFAULT 0,
                                created_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                                updated_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                PRIMARY KEY (portfolio_id, tech_id),
                                KEY idx_pt_tech (tech_id),
                                CONSTRAINT fk_pt_portfolio FOREIGN KEY (portfolio_id) REFERENCES portfolios(id) ON DELETE CASCADE,
                                CONSTRAINT fk_pt_tech      FOREIGN KEY (tech_id)      REFERENCES techs(id)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- One-table taxonomy: labels attached directly to an entity
-- =========================================
CREATE TABLE entity_labels (
                               id           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                               target_type  ENUM('portfolio','tech','blog') NOT NULL,  -- what this label is attached to
                               target_id    BIGINT UNSIGNED NOT NULL,                  -- id in portfolios/techs/blogs
                               kind         ENUM('category','tag') NOT NULL,           -- your two label types
                               slug         VARCHAR(120) NOT NULL,                     -- machine key, e.g. 'web-app', 'frontend'
                               name         VARCHAR(120) NOT NULL,                     -- display name, e.g. 'Web App', 'Frontend'
                               weight       INT NOT NULL DEFAULT 0,                     -- optional ordering
                               created_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                               updated_at   DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                               PRIMARY KEY (id),

    -- avoid duplicates of the same label on the same entity
                               UNIQUE KEY uk_entity_label (target_type, target_id, kind, slug),
                               KEY idx_entity_label_target (target_type, target_id),
                               KEY idx_entity_label_slug_kind (slug, kind)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================
-- TESTIMONIALS (written by logged in user about the site owner)
-- =========================================
CREATE TABLE testimonials (
                              id                 BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                              author_user_id     BIGINT UNSIGNED NULL,        -- FK for edit permissions (nullable if user is deleted)
                              author_name        VARCHAR(120)  NOT NULL,      -- snapshot for display
                              author_avatar_url  VARCHAR(1024) NULL,          -- snapshot for display
                              author_title       VARCHAR(120)  NULL,          -- optional (e.g., "PM at Acme")
                              body               TEXT NOT NULL,
                              status             TINYINT NOT NULL DEFAULT 1,  -- 1=public, 0=hidden, 2=pending
                              pinned             TINYINT(1) NOT NULL DEFAULT 0,
                              created_at         DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
                              updated_at         DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

                              PRIMARY KEY (id),
                              KEY idx_testimonials_author (author_user_id),
                              KEY idx_testimonials_status (status, pinned, created_at), -- handy for homepage queries
                              CONSTRAINT fk_testimonials_author
                                  FOREIGN KEY (author_user_id) REFERENCES users(id) ON DELETE SET NULL,
                              CONSTRAINT chk_testimonials_status CHECK (status IN (0,1,2))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
