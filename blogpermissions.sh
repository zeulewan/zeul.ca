#!/bin/bash

# Define the blog folder path
BLOG_DIR="blog"


# Set the ownership to www-data for the blog directory and all its contents
chown -R www-data:www-data "$BLOG_DIR"

# Set the permissions to rwxrwx--- for the blog directory and all its contents
chmod -R 770 "$BLOG_DIR"

echo "The blog directory has been set to rwxrwx--- with www-data as the owner and group."
