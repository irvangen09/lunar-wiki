# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added

- Plugin bootstrap with PHP 8.0 environment check and a lightweight autoloader for the `Lunar\` namespace.
- Public API (`includes/public-api.php`) exposing structural identity, field, author, and game menu/tile functions for Lunar Theme and Lunar SEO.
- `wiki_article` custom post type.
- `game` taxonomy (hierarchical: franchise to specific title).
- `content_type` taxonomy (non-hierarchical).
- `wiki_field` taxonomy (not bound to any post type by default; admin menu link added manually).
- Infobox field-source integration with Lunar Blocks: registers `wiki_field` as the data source for the Infobox field dropdown, and syncs saved Infobox data into post meta.
- Secondary navigation menu term meta on the `game` taxonomy, selectable per specific title.
- Optional Polylang integration registering `game` and `content_type` as translatable taxonomies.
- Custom destination URL and custom image term meta on the `game` taxonomy, with a Media Library picker.
- Update Notes meta box on the Wiki Article edit screen.
- Author Role and Author Social Links fields on the WordPress user profile screen.
- Shared nonce verification service used by all meta-saving handlers.
- Initial "Role" term seeding for the `wiki_field` taxonomy, run once via `admin_init`.

### Documentation

- Cross-reference: `LUNAR_BLOCKS_WIKI_INTEGRATION_CONTRACT.md` §4.2.1 clarified that `Infobox_Sync::extract_value()` reading rendered HTML for the rich-text `value` attribute is an intra-plugin concern of Lunar Blocks, not a violation of the cross-plugin regex prohibition in §4.2. No change to hook names, signatures, or `$field_data` structure.