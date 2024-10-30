#!/bin/bash

# Configuration
GIT_REPO=$(pwd)
SVN_REPO=$(realpath "../moderation-api-automated-content-moderation")
PLUGIN_SLUG="moderation-api-automated-content-moderation"

# Ensure we're in the Git repository
cd "$GIT_REPO" || exit 1

# Get the latest version from Git
VERSION=$(git describe --tags --abbrev=0)
VERSION=${VERSION#v}  # Remove 'v' prefix if present

# Confirm with the user
read -p "Release version $VERSION? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    exit 1
fi

# Update Git repository
git checkout main
git pull origin main

# Ensure the tag exists
if ! git rev-parse "$VERSION" >/dev/null 2>&1; then
    echo "Tag $VERSION does not exist. Creating it now."
    git tag "$VERSION"
    git push origin "$VERSION"
fi

# Update SVN repository
cd "$SVN_REPO" || exit 1
svn update

# Remove existing files in trunk
rm -rf trunk/*

# Copy files from Git to SVN trunk
cp -R "$GIT_REPO"/* trunk/

# Copy assets
mkdir -p assets
cp -R "$GIT_REPO/assets"/* assets/

# Remove unnecessary files
rm -rf trunk/.git trunk/.gitignore

# Add new files to SVN
svn add trunk/* assets/* --force

# Remove deleted files from SVN
svn status | grep '^\!' | sed 's/! *//' | xargs -I% svn rm %@

# Commit changes to trunk
svn commit -m "Update to version $VERSION"

# Create new tag
svn copy trunk "tags/$VERSION"
svn commit -m "Tagging version $VERSION"

echo "Plugin $PLUGIN_SLUG version $VERSION has been successfully released!"