# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Unreleased]

### Added

- Plugin bootstrap with PHP 8.0 environment check and a lightweight autoloader for the `Lunar\Wiki\` namespace.
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
- High-level query helpers in the public API: `lunar_wiki_query_wiki_articles()` wraps `WP_Query` with the correct post type, and `lunar_wiki_get_content_type_terms()` returns the content_type terms actually used by articles under a given game term.
- Registration with Lunar SEO's supported post types filter, so `wiki_article` receives the same title/meta template resolution, editor override, and Article schema as native posts when Lunar SEO is active.

### Documentation

- Clarified in `docs/LUNAR_BLOCKS_WIKI_INTEGRATION_CONTRACT.md` (§4.2.1) that `Infobox_Sync::extract_value()` in Lunar Blocks reading rendered HTML for the rich-text `value` attribute is an internal implementation detail of that plugin, not a cross-plugin regex-parsing pattern. No change to hook names, signatures, or the `$field_data` structure passed to listeners.
- New `docs/LUNAR_SEO_WIKI_INTEGRATION_CONTRACT.md`, defining the `lunar_seo_supported_post_types` filter shared with Lunar SEO.

### Fixed

- Renamed internal namespaces from `Lunar\Content`, `Lunar\Users`, `Lunar\Services` to `Lunar\Wiki\Content`, `Lunar\Wiki\Users`, `Lunar\Wiki\Services`, scoping them under this plugin's own namespace to avoid class-name collisions with other plugins in the ecosystem that use similarly generic namespace names for their own Shared Services. Directory structure under `includes/` is unchanged; only namespace declarations, `use` imports, and the plugin's autoloader prefix were updated.