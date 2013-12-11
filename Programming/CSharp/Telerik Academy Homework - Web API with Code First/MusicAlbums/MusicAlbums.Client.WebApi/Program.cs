using MusicAlbums.Client.Model;
using Newtonsoft.Json;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Net.Http;
using System.Net.Http.Formatting;
using System.Net.Http.Headers;
using System.Text;
using System.Threading.Tasks;

namespace MusicAlbums.Client.WebApi
{
    class Program
    {
        private static string serverUri;
        private const string JsonFormat = "json";
        private const string XmlFormat = "xml";

        static void Main(string[] args)
        {
            InitServerUri();


            var clientXml = new HttpClient
            {
                BaseAddress = new Uri(serverUri)
            };

            var acceptXmlFormat = string.Format("application/{0}", XmlFormat);
            clientXml.DefaultRequestHeaders.Accept.Add(new
                MediaTypeWithQualityHeaderValue(acceptXmlFormat));

            var newAlbum1 = new Album("Test Album 1")
            {
                Producer = "Test Producer",
            };
            CreateAlbum(clientXml, XmlFormat, newAlbum1);

            var newAlbum2 = new Album("Test Album 2")
            {
                Producer = "Test Producer"
            };
            newAlbum2.Artists.Add(new Artist("Artist"));
            CreateAlbum(clientXml, XmlFormat, newAlbum2);

            var newAlbum3 = new Album("Test Album 3")
            {
                Producer = "New Test Producer"
            };
            CreateAlbum(clientXml, XmlFormat, newAlbum3);


            var clientJson = new HttpClient
            {
                BaseAddress = new Uri(serverUri)
            };

            var acceptJsonFormat = string.Format("application/{0}", JsonFormat);
            clientJson.DefaultRequestHeaders.Accept.Add(new
                MediaTypeWithQualityHeaderValue(acceptJsonFormat));


            HttpResponseMessage responseJson =
                clientJson.GetAsync("albums").Result;

            if (responseJson.IsSuccessStatusCode)
            {
                var albums = responseJson.Content
                    .ReadAsAsync<IEnumerable<Album>>().Result;
                foreach (var alb in albums)
                {
                    Console.WriteLine("{0,5} {1,-40} {2}",
                        alb.Id, alb.Title, alb.Producer);
                }
            }
            else
                Console.WriteLine("{0} ({1})",
                    (int)responseJson.StatusCode, responseJson.ReasonPhrase);

        }

        private static void InitServerUri()
        {
            var protocol = "http";
            var uri = "localhost";
            int? port = 51348;
            var api = "api";

            if(port != null)
            {
                serverUri = string.Format("{0}://{1}:{2}/{3}", protocol, uri, port, api);
            }
            else
            {
                serverUri = string.Format("{0}://{1}/{2}", protocol, uri, api);
            }
        }

        static async void CreateAlbum(HttpClient httpClient, string format, Album album)
        {
            HttpContent postContent = new StringContent(JsonConvert.SerializeObject(album));
            postContent.Headers.ContentType = new System.Net.Http.Headers.MediaTypeHeaderValue(string.Format("application/{0}", format));

            var response = await httpClient.PostAsync("albums", postContent);
            //end of slowing code

            try
            {
                response.EnsureSuccessStatusCode();
                Console.WriteLine("Album created!");
            }
            catch (Exception)
            {
                Console.WriteLine("Error creating album");
            }
        }

        static async void CreateArtist(HttpClient httpClient, string format, Artist artist)
        {
            HttpContent postContent = new StringContent(JsonConvert.SerializeObject(artist));
            postContent.Headers.ContentType = new System.Net.Http.Headers.MediaTypeHeaderValue(string.Format("application/{0}", format));

            var response = await httpClient.PostAsync("artists", postContent);
            //end of slowing code

            try
            {
                response.EnsureSuccessStatusCode();
                Console.WriteLine("Artist created!");
            }
            catch (Exception)
            {
                Console.WriteLine("Error creating artist");
            }
        }

        static async void CreateSong(HttpClient httpClient, string format, Song song)
        {
            HttpContent postContent = new StringContent(JsonConvert.SerializeObject(song));
            postContent.Headers.ContentType = new System.Net.Http.Headers.MediaTypeHeaderValue(string.Format("application/{0}", format));

            var response = await httpClient.PostAsync("songs", postContent);
            //end of slowing code

            try
            {
                response.EnsureSuccessStatusCode();
                Console.WriteLine("Song created!");
            }
            catch (Exception)
            {
                Console.WriteLine("Error creating song");
            }
        }
    }
}
