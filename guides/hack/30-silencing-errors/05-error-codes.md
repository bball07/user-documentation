This page lists the most common error codes, and suggests how to fix
them. You can see the full list of error codes in
[error_map.ml](https://github.com/facebook/hhvm/blob/master/hphp/hack/test/errors/error_map.ml).

## 1002: Top-level code

@@ error-codes-examples/1002_toplevel.php @@

Why it's bad: Top-level code is not type checked.

Suggestions: Put your code in a function and use the `__EntryPoint`
attribute.

## 2049: Unbound name

@@ error-codes-examples/2049_unbound_name.php @@

Why it's bad: This is usually a sign that a name is incorrect.

It may be useful for calling parts of the PHP standard library that
the global name check is not aware of.

Suggestions: Check your spelling. Use safe Hack APIs rather than
legacy PHP APIs.

## 2050: Undefined Variable

@@ error-codes-examples/2050_undefined_var.php @@

Why it's bad: This is usually a sign that a variable name is incorrect.

It may be useful for accessing PHP constants (such as `$GLOBALS` or `$_GET`)
which the typechecker is unaware of.

Suggestions: Check your spelling. Use safe Hack APIs rather than
legacy PHP APIs.

## 4005: Array access on a type that doesn't support indexing

@@ error-codes-examples/4005_array_access.php @@

Why it's bad: Indexing values that don't support values can produce
surprising behavior. The runtime will log a warning and return null,
leading to runtime type errors later.

Suggestions: Refactor the code to use a Hack array or a
`KeyedContainer`.

## 4006: Array append on an inappropriate type

@@ error-codes-examples/4006_array_append.php @@

Why it's bad: Appending to other types (e.g. `int`) is undefined and
may throw an exception or convert the value to an array.

Suggestions: If the type isn't specific enough, use `as` (e.g. `as
vec<_>`) to perform a runtime type check.

## 4032: Missing return type

@@ error-codes-examples/4032_missing_return.php @@

Why it's bad: When the typechecker does not know the return type, it
cannot check operations on the value returned.

Suggestions: Add a return type to your function. If you're unsure of
the type, consider using `__Soft`. You may also want to consider a
`mixed` or `dynamic` return type.

## 4045: Array without type parameter

```
function foo(array $_): void {}
```

Why it's bad: The typechecker knows very little about how the array is
structured.

Suggestions: Use `darray`, `varray` or `varray_or_darray` instead. If
you still want to use `array`, specify the type e.g. `array<mixed>`.

## 4051: Accessing a shape with an invalid field name

@@ error-codes-examples/4051_field_name.php @@

Why it's bad: The runtime may coerce values and access other fields of
your shape. The typechecker also does not know what type `$value` has.

Suggestions: Use a valid shape key: a string (recommended), an
integer, or a class constant.

## 4053: Member not found

@@ error-codes-examples/4053_member_not_found.php @@

Why it's bad: Accessing a non-existent method will cause a runtime
error. Accessing a non-existent property will log a notice and return null.

Suggestions: Ensure that the object you're accessing actually has the
type you're expecting.

## 4057: Missing shape field

@@ error-codes-examples/4057_missing_field.php @@

Why it's bad: Returning a shape that's missing fields will cause
errors when code tries to access those fields later. Note that shape
fields are not enforced when calling or returning from functions.

Suggestions: Change your shape type to use optional fields.

## 4063: Nullable container access

@@ error-codes-examples/4063_null_container.php @@

Why it's bad: indexing a `null` returns null, leading to
runtime type errors later.

Suggestions: Check that the value is non-null with `nullthrows` or
assert with `$items as nonnull`.

## 4064: Accessing members on a nullable object

@@ error-codes-examples/4064_nullable.php @@

Why it's bad: Accessing a property or a method on `null` will throw an
exception.

Suggestions: Check that the value is non-null with `nullthrows` or
assert with `$m as nonnull`.

## 4101: Wrong number of type parameters

@@ error-codes-examples/4101_type_params.php @@

Why it's bad: If the typechecker doesn't have full information about a
class declaration, it cannot fully check code that uses the class.

Suggestions: Add the necessary type parameters. You can usually use
`mixed` or `nothing` as the type parameter on base classes.

Note that this is only required for declarations. Hack can infer type
parameters inside function and method bodies.

## 4107: Unbound name (type checking)

@@ error-codes-examples/4107_unbound_name_typing.php @@

Why it's bad: This is usually a sign that a name is incorrect.

It may be useful for calling parts of the PHP standard library that
the type checker is not aware of.

Suggestions: Check your spelling. Use safe Hack APIs rather than
legacy PHP APIs.

## 4108: Undefined shape field

@@ error-codes-examples/4108_undef_field.php @@

Why it's bad: Accessing an undefined field may throw an exception or
return an unexpected value (for open shapes).

Suggestions: Ensure that your shape type declaration has the fields
you're using.

## 4110: Bad type in expression

@@ error-codes-examples/4110_bad_type.php @@

Why it's bad: Using the wrong type can result in runtime errors (for
enforced types), errors later (for unenforced types, such as erased
generics) or surprising coercions (e.g. for arithmetic).

Suggestions:

If the type is too broad (e.g. using `mixed`), use `as SpecificType`
to assert the specific runtime type. If you're not sure of the type,
consider using `<<__Soft>>` type hints on function signatures.

If the type is coming from very dynamic code, consider using the
`dynamic` type.

## 4166: Unknown field in shape (deprecated)

This error code is deprecated. The typechecker now produces error 4110
in these situations.

```
function test(shape('a' => string) $s): shape() {
  /* HH_FIXME[4166] Extra fields in the shape. */
  return $s;
}
```

Why it's bad: Passing extra fields in a shape can lead to surprising
results when converting shapes to arrays.

Suggestions: Use a field type declaration with optional fields instead.

## 4128: Using deprecated code

@@ error-codes-examples/4128_use_deprecated.php @@

Why it's bad: Using functions or classes that have been marked as
deprecated prevents cleanup of old APIs.

Suggestions: `__Deprecated` takes a message which describes why
something is deprecated. Take a look at that message to learn the new
API.

## 4165: Accessing optional shape field

@@ error-codes-examples/4165_optional_field.php @@

Why it's bad: This code will throw an exception if the shape doesn't
have this field.

Suggestions: Use `Shapes::idx` instead, so you can explicitly handle
the missing field.

## 4193: Illegal XHP child

@@ error-codes-examples/4193_xhp_child.php @@

Why it's bad: XHP expects child elements to be instance of `XHPChild`.

Suggestions: Use `as` to assert a narrower type, or convert values to
a valid XHP child, such as a string.

## 4276: Truthiness check (deprecated)

```
$x = null;
if ($x) {
  echo "not null";
}

$y = '0';
if ($y) {
  echo "truthy string";
}
```

This error is deprecated, as it was making it harder to convert
partial mode files to strict. We still
recommend avoiding this code style.

Why it's bad: Truthiness rules can be surprising. `''` is falsy, but
so is `'0'`.

Suggestions: Use `is null`, `Str\is_empty` or `=== 0` when checking
for empty values.

## 4297: Type inference failed

@@ error-codes-examples/4297_infer.php @@

Why it's bad: If the type checker cannot infer the type, it cannot
check usage of values with that type.

This usually occurs with anonymous functions, but can also occur when
working with generic containers like `dict` or `vec`.

Suggestions: For anonymous functions, add a type annotation.

```
$f = (MyA $x) ==> $x->doStuff();
```

Alternatively, use a type assertion `($x as MyA)->doStuff()`.

When type inference fails on generic containers, add a type annotation
on the declaration.

```
$d = dict<string, string>[];
```

## 4323: Type constraint violation

@@ error-codes-examples/4323_constraints.php @@

Why it's bad: if a type has constraints on how it can be used, and you
break those constraints, it may not work as expected.

In this example, we define a `dict` with `mixed` keys. We can then
insert values that aren't `arraykey` types, leading to surprising
value conversions.

Suggestions: Look carefully at the error message to see what types are
supported for the generic you're using.
