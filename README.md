## Structured Data

### Install

1. Download sourcecode as ZIP from repository
2. Go to WP admin -> plugins and click "Upload Plugin"
3. Upload ZIP file and click "Install Now"
4. Wait for the installer to extract and install code
5. Click Activate Plugin. Then the plugin will be available in settings menu as Structured Data

### Use plugin.

example object to add in admin menu -> settings -> Structured Data

1. Pages can be targeted by an absolute url, relative url or wordpress post/page slug
2. First page in object below uses full url to target and insert structured data
3. Second page in object below uses relative url to target and insert structured data
4. First page in object below uses the slug to target and insert structured data

```
{
  "http://example.com/example-page": {
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
      {
        "@type": "Question",
        "name": "Vem dricker mest kaffe i v\u00e4rlden?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Finl\u00e4ndarna dricker mest kaffe i hela v\u00e4rlden, med ca 9,9 kg om \u00e5ret, vilket motsvarar ett snitt p\u00e5 3,5 koppar om dagen. P\u00e5 andra plats kommer vi svenskar, som dricker 3,2 koppar om dagen. <a href=https://www.kaffeinformation.se/statistik/>L\u00e4s mer statistik h\u00e4r!</a>"
        }
      },
      {
        "@type": "Question",
        "name": "Vem inf\u00f6rde kaffe i Sverige?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "Det s\u00e4gs att kung Karl XII tog kaffet till Sverige. Han reste till Turkiet ofta, d\u00e4r det bj\u00f6ds p\u00e5 mycket kaffe och tog d\u00e4rf\u00f6r med sig en turkisk kaffekokare hem till Sverige."
        }
      },
      {
        "@type": "Question",
        "name": "N\u00e4r kaffet kom till Sverige?",
        "acceptedAnswer": {
          "@type": "Answer",
          "text": "P\u00e5 1680-talet b\u00f6rjade vi i Sverige importera sm\u00e5 m\u00e4ngder av kaffe, som s\u00e5ldes p\u00e5 landets apotek. F\u00f6rst p\u00e5 mitten av 1800-talet blev kaffet en svensk folkdryck. <a href=https://www.kaffeinformation.se/grunder/kaffets-historia/> L\u00e4s mer om kaffets historia h\u00e4r.</a>"
        }
      }
    ]
  },
  "example-page/example-nested-page": {
    "@context": "https://schema.org",
    ...
  },
  "example-page-slug": {
    "@context": "https://schema.org",
    ...
  }
}
```