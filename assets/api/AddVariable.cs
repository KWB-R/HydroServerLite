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
    class VariableHelper
    {
        public void AddVariable()
        {
            string url = "http://worldwater.byu.edu/app/index.php/default/services/api/variables";

            System.Net.HttpWebRequest request = (HttpWebRequest)System.Net.HttpWebRequest.Create(url);

            request.Method = "POST";

            request.ContentType = "application/json";

            using (var streamWriter = new StreamWriter(request.GetRequestStream()))
            {
                string json = @"{'user': 'admin',
                        'password': 'password',
                        'VariableCode': 'AQTC',
                        'VariableName':'Color',
                        'Speciation':'Unknown',
                        'VariableUnitsID':189,
                        'SampleMedium':'Groundwater',
                        'ValueType':'Sample',
                        'IsRegular':1,
                        'TimeSupport':0,
                        'TimeUnitsID':100,
                        'DataType':'Average',
                        'GeneralCategory':'Hydrology',
                        'NoDataValue': 999}";


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
