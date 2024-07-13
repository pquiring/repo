<?php

  //Windows winget private REST repo in PHP (by : Peter Quiring) v0.1 [2024-07-11]

  //source : http://github.com/pquiring/repo/tree/main/windows/amd64

  //to install repo (requires admin rights) : winget source add JavaForce https://javaforce.sourceforge.net/windows/amd64 Microsoft.Rest

  //to install packages : winget install {package-name}

  //requires .htaccess with "FallbackResource /windows/amd64/winget.php"

  $debug = false;

  header('Content-Type: application/json; charset=utf-8');

  $path = trim(strtok($_SERVER['REQUEST_URI'], '?'), '/');

  if ($debug) {
    //note : you must make 'access.log' group writable (mode 660)
    $f = fopen('/home/project-web/javaforce/access.log', 'a');
    fwrite($f, $path . "\n");
    fclose($f);
  }

  $elements = explode('/', $path);

  $base = 'https://javaforce.sourceforge.net/windows/amd64';

  array_shift($elements);  // remove windows
  array_shift($elements);  // remove amd64

  $data = "";

  switch ($elements[0]) {
    case "information":
      $data .= '{';
      $data .= '"Data":{';
      $data .= '"SourceIdentifier":"JavaForce",';
      $data .= '"ServerSupportedVersions": ["1.7.0"]';
      $data .= '}';
      $data .= '}';
      break;
    case "packages":
      $count = count($elements);
      $first = true;
      switch ($count) {
        case 1:
          // /packages
          $data .= '{';
          $data .= '"Data":[';
          foreach (new DirectoryIterator('.') as $file) {
            if($file->isDot()) continue;
            $name = $file->getFilename();
            if(!str_ends_with($name, ".msi")) continue;
            $parts = explode('-', $name);
            $count = count($parts);
            $pkg = "";
            $ver = "";
            if ($count == 4) {
              //some packages have -client or -server in their names
              $pkg = $parts[0] . "-" . $parts[1];
              $ver = $parts[2];
            } else {
              $pkg = $parts[0];
              $ver = $parts[1];
            }
            if ($first) {
              $first = false;
            } else {
              $data .= ",";
            }
            $data .= '{"PackageIdentifier":';
            $data .= '"';
            $data .= $pkg;
            $data .= '"}';
          }
          $data .= ']';
          $data .= '}';
          break;
        case 2:
          // /packages/{packageid}
          $data .= '{';
          $data .= '"Data":{';
          $data .= '"PackageIdentifier":';
          $data .= '"';
          $data .= $elements[1];
          $data .= '"';
          $data .= '}';
          $data .= '}';
          break;
        case 3:
          // /packages/{packageid}/versions
          $reqpkg = $elements[1];
          $data .= '{';
          $data .= '"Data":[';
          switch ($elements[2]) {
            case "versions":
              foreach (new DirectoryIterator('.') as $file) {
                if($file->isDot()) continue;
                $name = $file->getFilename();
                if(!str_ends_with($name, ".msi")) continue;
                $parts = explode('-', $name);
                $count = count($parts);
                $pkg = "";
                $ver = "";
                if ($count == 4) {
                  //some packages have -client or -server in their names
                  $pkg = $parts[0] . '-' . $parts[1];
                  $ver = $parts[2];
                } else {
                  $pkg = $parts[0];
                  $ver = $parts[1];
                }
                if ($pkg == $reqpkg) {
                  version_defaultlocale($name, $pkg, $ver);
                }
              }
              $data .= ']';
              $data .= '}';
              break;
            default:
              $data = '[{"ErrorCode":"404","ErrorMessage":"Invalid Request"}]';
              break;
          }
          break;
        case 4:
          // /packages/{packageid}/versions/{version}
          $pkg = $elements[1];
          $ver = $elements[3];
          $name = $pkg . '-' . $ver . '-win64.msi';
          $data .= '{';
          $data .= '"Data":';
          version_defaultlocale($name, $pkg, $ver);
          $data .= '}';
          break;
        case 5:
          // /packages/{packageid}/versions/{version}/locales
          // /packages/{packageid}/versions/{version}/installers
          switch ($elements[4]) {
            case "locales":
              $pkg = $elements[1];
              $ver = $elements[3];
              $name = $pkg . '-' . $ver . '-win64.msi';
              $data .= '{';
              $data .= '"Data":[';
              locale($name, $pkg, $ver);
              $data .= ']';
              $data .= '}';
              break;
            case "installers":
              $pkg = $elements[1];
              $ver = $elements[3];
              $name = $pkg . '-' . $ver . '-win64.msi';
              $data .= '{';
              $data .= '"Data":[';
              installer($name, $pkg, $ver);
              $data .= ']';
              $data .= '}';
              break;
            default:
              $data = '[{"ErrorCode":"404","ErrorMessage":"Invalid Request"}]';
              break;
          }
          break;
        case 6:
          // /packages/{packageid}/versions/{version}/locales/{localeid}
          // /packages/{packageid}/versions/{version}/installers/{installid}
          switch ($elements[4]) {
            case "locales":
              $pkg = $elements[1];
              $ver = $elements[3];
              $name = $pkg . '-' . $ver . '-win64.msi';
              $locale = $elements[5];
              $data .= '{';
              $data .= '"Data":';
              locale($name, $pkg, $ver);
              $data .= '}';
              break;
            case "installers":
              $pkg = $elements[1];
              $ver = $elements[3];
              $name = $pkg . '-' . $ver . '-win64.msi';
              $install = $elements[5];
              $data .= '{';
              $data .= '"Data":';
              installer($name, $pkg, $ver);
              $data .= '}';
              break;
            default:
              $data = '[{"ErrorCode":"404","ErrorMessage":"Invalid Request"}]';
              break;
          }
          break;

      }
      break;
    case "packageManifests":
      // /packageManifests
      $count = count($elements);
      switch ($count) {
        case 2:
          // /packageManifests/{packageid}?Version={ver}
          $reqpkg = $elements[1];
          $ver = $_GET['Version'];
          $data .= '{';
          $data .= '"Data":';
          $data .= '{"PackageIdentifier":';
          $data .= '"';
          $data .= $reqpkg;
          $data .= '",';
          foreach (new DirectoryIterator('.') as $file) {
            if($file->isDot()) continue;
            $name = $file->getFilename();
            if(!str_ends_with($name, ".msi")) continue;
            $parts = explode('-', $name);
            $count = count($parts);
            $pkg = "";
            $ver = "";
            if ($count == 4) {
              //some packages have -client or -server in their names
              $pkg = $parts[0] . '-' . $parts[1];
              $ver = $parts[2];
            } else {
              $pkg = $parts[0];
              $ver = $parts[1];
            }
            if ($pkg == $reqpkg) {
              $data .= '"Versions":[';
              version_defaultlocale_installer($name, $pkg, $ver);
              $data .= ']';
            }
          }
          $data .= '}';
          $data .= '}';
          break;
        default:
          $data = '[{"ErrorCode":"404","ErrorMessage":"Invalid Request"}]';
          break;
      }
      break;
    case "manifestSearch":
      $keyword = "";
      try {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        $keyword = $json['Inclusions'][0]['RequestMatch']['KeyWord'];
      } catch (Throwable $e) {
        $f = fopen('/home/project-web/javaforce/access.log', 'a');
        fwrite($f, 'exception=' . $e->getMessage() . "\n");
        fclose($f);
        $keyword = "error";
      }
      if ($debug) {
        $f = fopen('/home/project-web/javaforce/access.log', 'a');
        fwrite($f, 'keyword=' . $keyword . "\n");
        fclose($f);
      }
      //search and print all packages that match this keyword exactly
      $data .= '{';
      $data .= '"Data":[';
      foreach (new DirectoryIterator('.') as $file) {
        if($file->isDot()) continue;
        $name = $file->getFilename();
        if(!str_ends_with($name, ".msi")) continue;
        $parts = explode('-', $name);
        $count = count($parts);
        $pkg = "";
        $ver = "";
        if ($count == 4) {
          //some packages have -client or -server in their names
          $pkg = $parts[0] . '-' . $parts[1];
          $ver = $parts[2];
        } else {
          $pkg = $parts[0];
          $ver = $parts[1];
        }
        if ($pkg == $keyword) {
          $data .= '{';
          $data .= '"PackageIdentifier":"' . $pkg . '",';
          $data .= '"PackageName":"' . $pkg . '",';
          $data .= '"Publisher":"JavaForce",';
          $data .= '"Versions":[';
          version($name, $pkg, $ver);
          $data .= ']';
          $data .= '}';
        }
      }
      $data .= ']';
      $data .= '}';
      break;
    default:
      $data = '[{"ErrorCode":"404","ErrorMessage":"Invalid Request"}]';
      break;
  }

  print $data;

  function locale($name, $pkg, $ver) {
    global $data, $base;
    $data .= '{"PackageLocale":"en-us",';
    $data .= '"Publisher":"JavaForce",';
    $data .= '"PackageName":"' . $pkg . '",';
    $data .= '"ShortDescription":"' . $pkg . '",';
    $data .= '"PackageUrl":"';
    $data .= $base;
    $data .= '/' . $name . '"';
    $data .= '}';
  }

  function version_defaultlocale($name, $pkg, $ver) {
    global $data, $base;
    $data .= '{';
    $data .= '"PackageVersion": ';
    $data .= '"' . $ver . '",';
    $data .= '"DefaultLocale":';
    locale($name, $pkg, $ver);
    $data .= '}';
  }

  function version_defaultlocale_installer($name, $pkg, $ver) {
    global $data, $base;
    $data .= '{';
    $data .= '"PackageVersion": ';
    $data .= '"' . $ver . '",';
    $data .= '"DefaultLocale":';
    locale($name, $pkg, $ver);
    $data .= ',"Installers":[';
    installer($name, $pkg, $ver);
    $data .= ']';
    $data .= '}';
  }

  function version($name, $pkg, $ver) {
    global $data, $base;
    $data .= '{';
    $data .= '"PackageVersion": ';
    $data .= '"' . $ver . '"';
    $data .= '}';
  }

  function installer($name, $pkg, $ver) {
    global $data, $base;
    $file = $pkg . '-' . $ver . '-win64.msi';
    $data .= '{';
    $data .= '"InstallerIdentifier": ';
    $data .= '"' . $pkg . '-' . $ver . '",';
    $data .= '"Architecture":"x64",';
    $data .= '"InstallerType":"msi",';
    $data .= '"UpgradeBehavior":"install",';
    $data .= '"InstallerLocale":"en-us",';
    $data .= '"Scope":"user",';
    $data .= '"InstallerSha256":"'. hash_file('sha256', $file) . '",';
    $data .= '"InstallerUrl":"';
    $data .= $base;
    $data .= '/' . $name . '"';
    $data .= '}';
  }
?>
