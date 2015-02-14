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
    class MethodHelper
    {
        public void AddMethod()
        {
            string url = "http://worldwater.byu.edu/app/index.php/default/services/api/methods";

            System.Net.HttpWebRequest request = (HttpWebRequest)System.Net.HttpWebRequest.Create(url);

            request.Method = "POST";

            request.ContentType = "application/json";

            using (var streamWriter = new StreamWriter(request.GetRequestStream()))
            {
                string json = @"{'user': 'admin',
                        'password': 'password',
                        'MethodDescription':'New method added by c#',
                        'MethodLink':'http://example.com',
                        'VariableID':0}";

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
