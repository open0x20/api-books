# api-books
HTTP API for managing ebooks in PDF format.

Fetches locally available PDFs and serves them via an HTTP API. Can also
provide cached cover images.

## Endpoints
| Method | Path          | Description                                                         |
|--------|---------------|---------------------------------------------------------------------|
| GET    | /list         | Lists all locally available PDFs.                                   |
| GET    | /book/{name}  | Serves the specified PDF.                                           |
| GET    | /cover/{name} | Serves the cover of the specified PDF. Will be cached on 2nd serve. |

### Examples
```
GET /list

HTTP/1.1 200 OK
{
  "meta": {
    "code": 200,
    "errors": []
  },
  "data": [
    {
      "name": "html_for_dummies_2nd_edition.pdf",
      "types": [
        {
          "type": "pdf",
          "url": "/book/html_for_dummies_2nd_edition.pdf"
        }
      ]
    }
  ]
}

```
```
GET /book/html_for_dummies_2nd_edition.pdf

HTTP/1.1 200 OK
Content-Type: application/pdf
<<blob>>
```
```
GET /cover/html_for_dummies_2nd_edition.pdf

HTTP/1.1 200 OK
Content-Type: image/jpeg
<<blob>>
```

### Configuration
Setup the paths to the PDFs in `config/services.yaml`. Simply add them
to the paths array. No trailing slash.
```
parameters:
    data_dir: '%kernel.project_dir%/var'
    allowed_types: ['epub','mobi','pdf']
    paths: [
            '/my/pdf/collection',
            '/home/user/Documents',
            '/mnt/rpi-nas/hdd/my-awesome-pdfs',
            '/var/www/html/books/var'
    ]
```
Files are being matched by the extensions provided in `allowed_types`.
