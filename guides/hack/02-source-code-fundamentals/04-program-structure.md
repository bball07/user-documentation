A Hack program consists of one or more source files; these source files should
have the extension `.hack`, and contain one or more declarations of the following kind,
in any order:

* alias declaration
* class
* enumerated type
* function
* inclusion directive
* interface
* namespace
* trait
* use

Consider the following file:

@@ program-structure-examples/hello-world.hack @@

In this example, the start-up function is called `main`; however, that was an arbitrary choice of name; it could just as easily
have been `run`, `do_it`, or `make_magic`. (What makes `main` the start-up function is the presence of the [attribute `__EntryPoint`](../attributes/predefined-attributes#__entrypoint).)

## Including Other Files

A file can import another file via [inclusion](script-inclusion.md).

## Legacy Files

The `.php` extension is still in use in older Hack code, though not recommended for new code. Hack code using
this extension *must* start with an optional shebang line (e.g. `#!/usr/bin/env hhvm`), followed by a header line. The header line can be one of the following:

- `<?hh // strict`: this is currently the recommended header, and makes Hack work in the documented ways
- `<?hh // partial`: this loosens several restrictions to ease migration from other languages. It is strongly
  discoraged for new code.
- `<?hh`: depending on the version of HHVM/Hack, this may be equivalent to strict, partial, or throw an error. It is strongly discouraged for both new and existing code.
- `<?hh // decl`: no longer supported

The following file is equivalent to the earlier example:

@@ program-structure-examples/legacy.php @@