Here is my realization of managing categories through REST API

I decided to choose Nested Set Model for keeping categories and their relations. For this purpose, I took a pretty good and well-documented package - `kalnoy/nestedset`. It extends a database with the additional columns `_lft`, `_rgt`, and `parent_id`. Also, it provides a convenient Trait, which allows an appropriate model to work with nested sets.

Added seeder, which generates a huge Nested Set of categories with Depth = 4 and randomly chosen child categories for each parent.

Was thinking about possible routes infrastructure and decided to provide wide abilities for the front-end to work with the data. Along with the standard CRUD set, I added a few more routes. Here is the whole list:
- `GET` `/categories` - retrieves all categories with pagination. Request can be extended with the query parameters:
  - `only_root` - retrieve only root categories
  - `sort_by` - can be sorted now only by `name` field, but it can be easily extended
  - `sort_direction` - `ASC` or `DESC`
  - `search` - currently search only by name
- `GET` `/categories/{$catId}` - retrieve single category
- `POST` `/categories` - create a new Category. By passing `parent_id` field it can be attached to any existing category as a child.
- `PUT` `/categories/{$catId}` - update an exiting Category
- `DELETE` `/categories/{$catId}` - delete and existing Category

- `GET` `/subcategories/{$catId}` - can be used for fetching subcategories by passing parent category id, in case of showing only root categories. Available all parameters from `/categories` endpoint, except `only_root`.
- `GET` `/categories-tree` -  retries ALL existing categories with subcategories. Used when front-end want to get the whole tree in one response and then easily render it, without additional processing of parent/child search. On my localhost, I created a nested set with about 5K elements and Depth = 4. This request took about 400ms, which is pretty fast I think.

Each response (except tree) gives a resource or a collection of Categories. Here is an example of Category Resource:
```json
{
  "id": "Category ID",
  "name": "Category Name",
  "parent": "Parent Category Object - contains id and name",  
  "children": "List of Child Categories (only from next level, not nested structure)",
  "depth": "Show at which level current Category is",
  "has_children": "Bool field, which helps to determine whether the current category has any children or not"
}
```
Each request (except tree) can have additional query parameters `hide_children` and `hide_parent`. It can be useful for reducing the response size if these fields are not necessary.
