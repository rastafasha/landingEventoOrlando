// This file can be replaced during build by using the `fileReplacements` array.
// `ng build` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
  production: false,
  //apirest local
  // apiUrl: "http://127.0.0.1:8000/api",
  // apiUrlMedia: "http://127.0.0.1:8000/storage/app/",
  //remoto
  apiUrl: "https://ursigalletti.net/backend-api/public/api",
  apiUrlMedia: "https://ursigalletti.net/backend-api/storage/app/",
  clientIdSandbox: 'AVqPEgCGvjhPeugklbaUQUmujPZEVb53TZBYWUbjBUvppz4bOoXKgXot2sZtXQgH5AHsdSp-WYuB83nM',//ursi
  clientId: 'ATLlUShSXBpJNhgTm6M0H2l1yq42P9fYBmNGDYdKRAIAy8hMcf_wwnCUUIaUHev8LcrVKK_oUxUsYXSs'//ursi

};

/*
 * For easier debugging in development mode, you can import the following file
 * to ignore zone related error stack frames such as `zone.run`, `zoneDelegate.invokeTask`.
 *
 * This import should be commented out in production mode because it will have a negative impact
 * on performance if an error is thrown.
 */
// import 'zone.js/plugins/zone-error';  // Included with Angular CLI.
