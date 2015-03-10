using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Net;
using System.IO;
using System.Windows.Forms;

namespace APITest
{
    class SiteHelper
    {
        private void btnSite_Click(object sender, EventArgs e)
        {
            string url = "http://worldwater.byu.edu/app/index.php/default/services/api/sites";

            System.Net.HttpWebRequest request = (HttpWebRequest)System.Net.HttpWebRequest.Create(url);

            request.Method = "POST";

            request.ContentType = "application/json";

            using (var streamWriter = new StreamWriter(request.GetRequestStream()))
            {
                string json = @"{'user': 'admin',
                        'password': 'password',
                        'SourceID':20,
                        'SiteCode':'Te-15',
                        'SiteName':'test',
                        'Latitude':40.24182,
                        'Longitude':-111.61917,
                        'SiteType':'Stream',
                        'Elevation_m':1940.6,
                        'State':'Utah',
                        'County':'Utah',
                        'Comments':'citation001'}";

                json = json.Replace("'", "\"");
                streamWriter.Write(json);
                streamWriter.Flush();
                streamWriter.Close();
            }


            try
            {
                using (WebResponse response = request.GetResponse())
                {
                    using (var streamReader = new StreamReader(response.GetResponseStream()))
                    {
                        var result = streamReader.ReadToEnd();
                        MessageBox.Show(result);
                    }
                }
            }
            catch (WebException ex)
            {
                using (WebResponse response = ex.Response)
                {
                    HttpWebResponse httpResponse = (HttpWebResponse)response;
                    string errorCode = string.Format("Error code: {0} ", httpResponse.StatusCode);
                    using (Stream data = response.GetResponseStream())
                    using (var reader = new StreamReader(data))
                    {
                        string text = reader.ReadToEnd();
                        MessageBox.Show(errorCode + text);
                    }
                }
            }
        }
    }
}
