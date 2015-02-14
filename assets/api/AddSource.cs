using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.IO;
using System.Net;
using System.Windows.Forms;

namespace APITest
{
    class SourceHelper
    {
        private void AddSource()
        {
            string url = "http://worldwater.byu.edu/app/index.php/default/services/api/sources";

            System.Net.HttpWebRequest request = (HttpWebRequest)System.Net.HttpWebRequest.Create(url);
            request.Method = "POST";

            request.ContentType = "application/json";

            using (var streamWriter = new StreamWriter(request.GetRequestStream()))
            {
                string json = @"{'user': 'admin',
                        'password': 'password',
                        'organization':'test004',
                        'description':'test',
                        'link':'link001',
                        'name':'teva',
                        'phone':'123-456-7891',
                        'email':'email@email.com',
                        'address':'address001',
                        'city':'city001',
                        'state':'Utah',
                        'zipcode':'zipcode001',
                        'citation':'citation001',
                        'metadata':10 }";

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
